        <?php
        if (isset($_GET['id'])) {
          $stmt = $connection->prepare("SELECT * FROM users WHERE  uuid_user LIKE '{$_GET['id']}%'");
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $user = $result[0];
        }
        $dadosUser = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dadosUser['createUser'])) {
          try {
            $querySaveUser = "INSERT INTO users (uuid_user, name_user, cpf_user, passsword_user, email_user,level_user, status_user) VALUES(:uuid_user, :name_user, :cpf_user, :passsword_user, :email_user, :level_user, :status_user)";
            $cadUser = $connection->prepare($querySaveUser);
            $cadUser->bindParam(':uuid_user', uuidv4());
            $cadUser->bindParam(':name_user', $dadosUser['name_user']);
            $cadUser->bindParam(':cpf_user', tiraMascara($dadosUser['cpf_user']));
            $cadUser->bindParam(':passsword_user', md5(substr($dadosUser['cpf_user'], 0, 3) . substr($dadosUser['cpf_user'], -2)));
            $cadUser->bindParam(':email_user', $dadosUser['email_user']);
            $cadUser->bindParam(':level_user', $dadosUser['level_user']);
            $cadUser->bindParam(':status_user', $dadosUser['status_user']);

            $cadUser->execute();

            if ($cadUser->rowCount()) {
              echo '<script>alert("Usuários Criado com sucesso!"); history.go(-2); </script>';
            } else {
              echo '<script>alert("Erro ao Criar Usuários, tente novamente"); </script>';
            }
          } catch (PDOException $erro) {
            echo "<script>alert('Erro ao Criar Usuários, tente novamente'. $erro); </script>";
          }
        } elseif (isset($dadosUser['editUser'])) {
          try {
            $queryUpdateUser = "UPDATE users SET name_user = :name_user, cpf_user = :cpf_user, email_user = :email_user ,level_user = :level_user, status_user = :status_user WHERE id = {$user['id']}";
            $cadUser = $connection->prepare($queryUpdateUser);
            $cadUser->bindParam(':name_user', $dadosUser['name_user']);
            $cadUser->bindParam(':cpf_user', tiraMascara($dadosUser['cpf_user']));
            $cadUser->bindParam(':email_user', $dadosUser['email_user']);
            $cadUser->bindParam(':level_user', $dadosUser['level_user']);
            $cadUser->bindParam(':status_user', $dadosUser['status_user']);

            $cadUser->execute();

            if ($cadUser->rowCount()) {
              echo '<script>alert("Usuários Editado com sucesso!"); history.go(-2); </script>';
            } else {
              echo '<script>alert("Erro ao Atualizar os dados do usuários, tente novamente"); </script>';
            }
          } catch (PDOException $erro) {
            echo "<script>alert('Erro ao Atualizar os dados do usuários, tente novamente'. $erro); </script>";
          }
        }

        ?>
        <div class="card pd-20 pd-sm-40">
          <h6 class="card-body-title text-primary">
            <span class="mdi mdi-account-multiple-plus-outline tx-20"></span>
            &nbsp;Novo Usuários
          </h6>
          <p class="mg-b-20 mg-sm-b-30">
            Cadastre um novo usuários para o sistema.
          </p>
          <form action="" method="POST" data-parsley-validate>
            <div class="form-layout">
              <div class="row mg-b-25">
                <div class="col-lg-9">
                  <div class="form-group">
                    <label class="form-control-label">Nome do Usuário: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="name_user" value="<?= $user['name_user']; ?>" placeholder="Informe o nome do usuário" required>
                  </div>
                </div><!-- col-9 -->
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label">CPF: <span class="tx-danger">*</span></label>
                    <input class="form-control js_cpf" id="cpf_user" type="text" name="cpf_user" value="<?= $user['cpf_user']; ?>" placeholder="Informe o CPF" required>
                  </div>
                </div><!-- col-3 -->
                <div class="col-lg-6">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">E-Mail: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="email" name="email_user" value="<?= $user['email_user']; ?>" placeholder="Informe o E-mail Valido" required>
                  </div>
                </div><!-- col-6 -->
                <div class="col-lg-2">
                  <div id="slWrapper" class="form-group mg-b-10-force parsley-select">
                    <label class="form-control-label">Nível de Acesso: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" name="level_user" data-placeholder="Selecione o Nível " data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                      <option label="Selecione o Nível"></option>
                      <option value="1" <?= $user['level_user'] == 1 ? "selected" : '' ?>>Admistrador</option>
                      <option value="2" <?= $user['level_user'] == 2 ? "selected" : '' ?>>Editor</option>
                      <option value="3" <?= $user['level_user'] == 3 ? "selected" : '' ?>>Revisor</option>
                    </select>
                    <div id="slErrorContainer"></div>
                  </div>
                </div><!-- col-4 -->
                <div class="col-lg-2">
                  <div id="slWrapper" class="form-group mg-b-10-force parsley-select">
                    <label class="form-control-label">Status do Usuarios: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" name="status_user" data-placeholder="Selecione o Nível de Acesso" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                      <!-- <option label="Selecione o Nível de Acesso"></option> -->
                      <option value="1" <?= $user['status_user'] == 1 ? "selected" : '' ?>>Ativo</option>
                      <option value="0" <?= $user['status_user'] == 0 ? "selected" : '' ?>>Inativo</option>
                    </select>
                    <div id="slErrorContainer"></div>
                  </div>
                </div><!-- col-2 -->
                <div class="col-lg-2">
                  <div id="slWrapper" class="form-group mg-b-10-force parsley-select">
                    <label class="form-control-label text-center">
                      Senhas Padão
                      <span class="mx-1 mdi mdi-key-chain" data-toggle="tooltip" data-placement="top" title="Senha padrão: 3 primeiros + 2 ultimos digitos do CPF"></span>
                      <span id="toggleViewPass" class="mdi mdi-eye-outline"></span>
                    </label>
                    <input class="form-control" disabled type="password" id="passCPF" value="">
                  </div>
                </div><!-- col-2 -->
          </form>
        </div><!-- row -->

        <div class="form-layout-footer">
          <?php
          if (isset($_GET['id'])) {
          ?>
            <button type="submit" name="editUser" class="btn btn-primary mg-r-5 rounded">
              <span class="mdi mdi-content-save-edit-outline tx-20"></span>
              Salvar Edição
            </button>
          <?php } else { ?>
            <button type="submit" name="createUser" class="btn btn-primary mg-r-5 rounded">
              <i class="mdi mdi-content-save-check-outline tx-20"></i>
              Criar Usuário
            </button>
          <?php } ?>
          <button type="reset" class="btn btn-outline-danger border-0 rounded" onclick="history.go(-1)">
            <span class="mdi mdi-trash-can-outline tx-20"></span>
            Cancelar
          </button>
        </div><!-- form-layout-footer -->
        </div><!-- form-layout -->
        </div><!-- card -->
        <script>
          $(function() {
            'use strict';

            $('.select2').select2({
              minimumResultsForSearch: Infinity
            });

            // Initialize tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Initialize popover
            $('[data-popover-color="default"]').popover();

            // By default, Bootstrap doesn't auto close popover after appearing in the page
            // resulting other popover overlap each other. Doing this will auto dismiss a popover
            // when clicking anywhere outside of it
            $(document).on('click', function(e) {
              $('[data-toggle="popover"],[data-original-title]').each(function() {
                //the 'is' for buttons that trigger popups
                //the 'has' for icons within a button that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                  (($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false // fix for BS 3.3.6
                }

              });
            });
            // passCPF

            $('#cpf_user').change(function() {
              $("#cpf_user").unmask();;
              let cpf = $('#cpf_user').val();
              let p1 = cpf.substr(0, 3)
              let p2 = cpf.substr(-2)
              let pass = p1 + p2
              $('#passCPF').val(pass)

            })
            // toggle password visibility
            $('#toggleViewPass').on('click', function() {
              $(this).toggleClass('mdi-eye-off-outline').toggleClass('mdi-eye-outline'); // toggle our classes for the eye icon
              let input = document.querySelector('#passCPF');
              if (input.getAttribute('type') == 'password') {
                input.setAttribute('type', 'text');
              } else {
                input.setAttribute('type', 'password');
              }
              // alert('click') // activate the hideShowPassword plugin
            });

          });
        </script>
