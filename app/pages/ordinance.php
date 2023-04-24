        <?php
        if (isset($_GET['id'])) {
          $id = $_GET['id'];
          $stmt = $connection->prepare("SELECT * FROM portarias WHERE id = :id");
          $stmt->bindParam(':id', $id);
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $edit_ordinace = $result[0];
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
            if ($_FILES['arquivo']['name'] == '') {
              $queryEditOrdinance = "UPDATE portarias SET exercicio = :exercicio, nPort = :nPort, data_portaria = :data_portaria, tipo_portaria = :tipo_portaria, cargo = :cargo, agente = :agente, descricao_portaria = :descricao_portaria WHERE id = :id";
              $execEditOrdinance = $connection->prepare($queryEditOrdinance);
              $execEditOrdinance->execute(array(
                'id' => $id,
                ':exercicio' => $dados['exercicio'],
                ':nPort' => $dados['nPort'],
                ':data_portaria' => $dados['data_portaria'],
                ':tipo_portaria' => strtoupper($dados['tipo_portaria']),
                ':cargo' => strtoupper($dados['cargo']),
                ':agente' => strtoupper($dados['agente']),
                ':descricao_portaria' => nl2br($dados['descricao_portaria']),
              ));
            } else {
              // Verifica se o arquivo existe
              if (file_exists($edit_ordinace['arquivo'])) {
                // Apaga o arquivo
                unlink($edit_ordinace['arquivo']);
              }
              $uploaddir = file_exists('upload/portarias/') ? 'upload/portarias/' : mkdir('upload/portarias/', 0777, true);
              $uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);
              move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile);
              $queryEditOrdinance = "UPDATE portarias SET exercicio = :exercicio, nPort = :nPort, data_portaria = :data_portaria, tipo_portaria = :tipo_portaria, cargo = :cargo, agente = :agente, arquivo = :arquivo, descricao_portaria = :descricao_portaria WHERE id = :id";
              $execEditOrdinance = $connection->prepare($queryEditOrdinance);
              $execEditOrdinance->execute(array(
                'id' => $id,
                ':exercicio' => $dados['exercicio'],
                ':nPort' => $dados['nPort'],
                ':data_portaria' => $dados['data_portaria'],
                ':tipo_portaria' => strtoupper($dados['tipo_portaria']),
                ':cargo' => strtoupper($dados['cargo']),
                ':agente' => strtoupper($dados['agente']),
                'arquivo' => $uploadfile,
                ':descricao_portaria' => nl2br($dados['descricao_portaria']),
              ));
            }
            // Verifica se a consulta foi bem sucedida
            if ($execEditOrdinance->rowCount() > 0) {
              echo '
              <script>
                alert("Registro atualizado com sucesso!");
                setTimeout(function() {
                  history.go(-2);
                }, 2000);
              </script>
              <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <div class="d-flex align-items-center justify-content-start">
                  <i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
                  <span>Registro atualizado com sucesso!</span>
                </div><!-- d-flex -->
              </div><!-- alert -->';
            } else {
              echo '
              <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <div class="d-flex align-items-center justify-content-start">
                  <i class="icon ion-ios-close alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
                  <span>Não foi possível atualizar o registro.</span>
                </div><!-- d-flex -->
              </div><!-- alert -->';
            }
          } catch (PDOException $erro) {
            echo "<script>alert('Erro ao Atualizar, tente novamente'. $erro); </script>";
          }
        }

        ?>
        <div class="card pd-20 pd-sm-40">
          <h6 class="card-body-title text-primary">
            <span class="mdi mdi-file-document-plus-outline tx-20"></span>
            &nbsp;<?= isset($_GET['id']) ? 'Editar Portaria' : 'Nova Portaria' ?>
          </h6>
          <p class="mg-b-20 mg-sm-b-30">
            <?= isset($_GET['id']) ? 'Edite os dados de uma portaria publicada no site.' : 'Cadastre uma nova portaria que será publicada no site.' ?>

          </p>
          <form action="" method="POST" enctype="multipart/form-data" data-parsley-validate>
            <div class="form-layout">
              <div class="row mg-b-25">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label">Exercício: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="number" name="exercicio" min="2018" max="2099" step="1" value="<?= $edit_ordinace['exercicio'] ?>" placeholder="Informe o exercício" required>
                  </div>
                </div><!-- col-2 -->
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label">Nº da Portaria: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="number" min="1" max="999" step="1" name="nPort" value="<?= $edit_ordinace['nPort']; ?>" placeholder="Informe o Nº" required>
                  </div>
                </div><!-- col-2 -->
                <div class="col-lg-2">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Data da Portaria: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="date" name="data_portaria" value="<?= $edit_ordinace['data_portaria']; ?>" required>
                  </div>
                </div><!-- col-6 -->
                <div class="col-lg-4">
                  <div id="slWrapper" class="form-group mg-b-10-force parsley-select">
                    <label class="form-control-label">Tipo de Portaria: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" name="tipo_portaria" data-placeholder="Selecione o Tipo " data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                      <?php
                      $stmt = $connection->prepare("SELECT * FROM type_ordinance ORDER BY title_type_ordinance DESC");
                      $stmt->execute();
                      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      foreach ($result as $type_ordinace) {
                      ?>
                        <?= isset($_GET['id']) ? '' : '<option selected dirname label="Selecione o Tipo"></option>' ?>
                        <option <?= $edit_ordinace['tipo_portaria'] == $type_ordinace['id_type_ordinance'] ? 'selected' : '' ?> value="<?= $type_ordinace['id_type_ordinance'] ?>"><?= $type_ordinace['title_type_ordinance'] ?></option>
                      <?php } ?>
                    </select>
                    <div id="slErrorContainer"></div>
                  </div>
                </div><!-- col-4 -->

                <div class="col-lg-6">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Cargo: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="cargo" value="<?= $edit_ordinace['cargo']; ?>" placeholder="Informe o cargo" required>
                  </div>
                </div><!-- col-4 -->
                <div class="col-lg-6">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Nomeado(s): <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="agente" value="<?= $edit_ordinace['agente']; ?>" placeholder="Informe o(s) Nome(s) do(s) Servidore(s)" required>
                  </div>
                </div><!-- col-2 -->
                <div class="col-lg-12">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Descrição da Poratria: <span class="tx-danger">*</span></label>
                    <textarea rows="5" class="form-control" name="descricao_portaria" placeholder="Descreva a portaria" required><?= nl2br($edit_ordinace['descricao_portaria']); ?></textarea>
                  </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Arquivo da portaria: <span class="tx-danger">*</span></label>
                    <input type="file" class="form-control" id="arquivo" name="arquivo" <?= isset($_GET['id']) ? '' : 'required' ?>>
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

            $('#cpf_edit_ordinace').change(function() {
              $("#cpf_edit_ordinace").unmask();;
              let cpf = $('#cpf_edit_ordinace').val();
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
