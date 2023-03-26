        <?php
        if (isset($_GET['id'])) {
          $stmt = $connection->prepare("SELECT * FROM portarias WHERE  uuid_user LIKE '{$_GET['id']}%'");
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $user = $result[0];
        }
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // echo '<pre>';
        // var_dump($_FILES['arquivo']);
        // echo '</pre>';

        if (isset($dados['savePort'])) {
          $uploaddir = file_exists('upload/portarias/') ? 'upload/portarias/' : mkdir('upload/portarias/', 0777, true);
          $uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);

          try {
            move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile);

            $querySavePortaria = "INSERT INTO portarias (codeDocs, exercicio, nPort, data_portaria, tipo_portaria, cargo, agente, arquivo, descricao_portaria) VALUES(:codeDocs, :exercicio, :nPort, :data_portaria, :tipo_portaria, :cargo, :agente, :arquivo, :descricao_portaria)";
            $cadPort = $connection->prepare($querySavePortaria);
            $cadPort->bindParam(':codeDocs', base64_encode(date('Y-m-d H:i:s')));
            $cadPort->bindParam(':exercicio', $dados['exercicio']);
            $cadPort->bindParam(':nPort', $dados['nPort']);
            $cadPort->bindParam(':data_portaria', $dados['data_portaria']);
            $cadPort->bindParam(':tipo_portaria', strtoupper($dados['tipo_portaria']));
            $cadPort->bindParam(':cargo', strtoupper($dados['cargo']));
            $cadPort->bindParam(':agente', strtoupper($dados['agente']));
            $cadPort->bindParam(':arquivo', $uploadfile);
            $cadPort->bindParam(':descricao_portaria', nl2br($dados['descricao_portaria']));

            $cadPort->execute();

            if ($cadPort->rowCount()) {
              echo '<script>alert("Portaria publicada com sucesso!"); history.go(-2); </script>';
            } else {
              echo '<script>alert("Erro ao publicar portaria, por favor tente novamente "); </script>';
            }
          } catch (PDOException $erro) {
            echo "<script>alert('Erro ao publicar portaria, por favor tente novamente -'$erro); </script>";
          }
        } elseif (isset($dados['editPort'])) {
          try {
          } catch (PDOException $erro) {
            echo "<script>alert('Erro ao Atualizar os dados do usuários, tente novamente'. $erro); </script>";
          }
        }

        ?>
        <div class="card pd-20 pd-sm-40">
          <h6 class="card-body-title text-primary">
            <span class="mdi mdi-file-document-plus-outline tx-20"></span>
            &nbsp;Nova Legislação
          </h6>
          <p class="mg-b-20 mg-sm-b-30">
            Cadastre uma nova Legislação que será publicada no site.
          </p>
          <form action="" method="POST" enctype="multipart/form-data" data-parsley-validate>
            <div class="form-layout">
              <div class="row mg-b-25">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label">Exercício: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="number" name="exercicio" min="2018" max="2099" step="1" value="" placeholder="Informe o exercício" required>
                  </div>
                </div><!-- col-2 -->
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label">Nº da Portaria: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="number" min="1" max="999" step="1" name="nPort" value="<?= $user['nPort']; ?>" placeholder="Informe o Nº" required>
                  </div>
                </div><!-- col-2 -->
                <div class="col-lg-2">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Data da Portaria: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="date" name="data_portaria" value="<?= $user['data_portaria']; ?>" required>
                  </div>
                </div><!-- col-6 -->
                <div class="col-lg-4">
                  <div id="slWrapper" class="form-group mg-b-10-force parsley-select">
                    <label class="form-control-label">Tipo de Portaria: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" name="tipo_portaria" data-placeholder="Selecione o Tipo " data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                      <option label="Selecione o Tipo"></option>
                      <option value="NOMEAÇÃO">NOMEAÇÃO</option>
                      <option value="EXONERAÇÃO">EXONERAÇÃO</option>
                      <option value="DESIGNAÇÃO">DESIGNAÇÃO</option>
                      <option value="INSTITUIÇÃO DE COMITÊ/COMISSÃO">INSTITUIÇÃO DE COMITÊ/COMISSÃO</option>
                    </select>
                    <div id="slErrorContainer"></div>
                  </div>
                </div><!-- col-4 -->

                <div class="col-lg-6">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Cargo: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="cargo" value="<?= $user['email_user']; ?>" placeholder="Informe o cargo" required>
                  </div>
                </div><!-- col-4 -->
                <div class="col-lg-6">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Nomeado(s): <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="agente" value="<?= $user['agente']; ?>" placeholder="Informe o(s) Nome(s) do(s) Servidore(s)" required>
                  </div>
                </div><!-- col-2 -->
                <div class="col-lg-12">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Descrição da Poratria: <span class="tx-danger">*</span></label>
                    <textarea rows="5" class="form-control" name="descricao_portaria" placeholder="Descreva a portaria" required></textarea>
                  </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Arquivo da portaria: <span class="tx-danger">*</span></label>
                    <input type="file" class="form-control" id="arquivo" name="arquivo" required>
                  </div>
                </div><!-- col-12 -->
          </form>
        </div><!-- row -->

        <div class="form-layout-footer">
          <?php
          if (isset($_GET['id'])) {
          ?>
            <button type="submit" name="editPort" class="btn btn-primary mg-r-5 rounded">
              <span class="mdi mdi-content-save-edit-outline tx-20"></span>
              Salvar Edição
            </button>
          <?php } else { ?>
            <button type="submit" name="savePort" class="btn btn-primary mg-r-5 rounded">
              <i class="mdi mdi-content-save-check-outline tx-20"></i>
              Publicar Portaria
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
