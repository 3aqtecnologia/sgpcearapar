        <?php
        if (isset($_GET['id'])) {
          $id = $_GET['id'];
          $stmt = $connection->prepare("SELECT * FROM legislation WHERE idLegislation = :id");
          $stmt->bindParam(':id', $id);
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $edit_legislation = $result[0];
        }
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // echo '<pre>';
        // var_dump($_FILES['$dados']);
        // echo '</pre>';

        if (isset($dados['saveLegislation'])) {
          $uploaddir = file_exists('upload/legislation/') ? 'upload/legislation/' : mkdir('upload/legislation/', 0777, true);
          $uploadfile = $uploaddir . basename($_FILES['fileLegislation']['name']);

          try {
            move_uploaded_file($_FILES['fileLegislation']['tmp_name'], $uploadfile);

            $querySaveLegislation = "INSERT INTO legislation (typeLegislation, instanceLegislation, dateLegislation, titleLegislation,  descriptionLegislation, fileLegislation) VALUES(:typeLegislation, :instanceLegislation, :dateLegislation, :titleLegislation, :descriptionLegislation, :fileLegislation)";
            $cadLegislation = $connection->prepare($querySaveLegislation);
            $cadLegislation->bindParam(':typeLegislation', $dados['typeLegislation']);
            $cadLegislation->bindParam(':instanceLegislation', $dados['instanceLegislation']);
            $cadLegislation->bindParam(':dateLegislation', $dados['dateLegislation']);
            $cadLegislation->bindParam(':titleLegislation', $dados['titleLegislation']);
            $cadLegislation->bindParam(':descriptionLegislation', nl2br($dados['descriptionLegislation']));
            $cadLegislation->bindParam(':fileLegislation', $uploadfile);

            $cadLegislation->execute();

            if ($cadLegislation->rowCount()) {
              echo '<script>alert("Legislação publicada com sucesso!"); history.go(-2); </script>';
            } else {
              echo '<script>alert("Erro ao publicar Legislação, por favor tente novamente "); </script>';
            }
          } catch (PDOException $erro) {
            echo "<script>alert('Erro ao publicar portaria, por favor tente novamente -'$erro); </script>";
          }
        } elseif (isset($dados['editLegislation'])) {
          try {
            if ($_FILES['fileLegislation']['name'] == '') {
              $queryEditLegislation = "UPDATE legislation SET typeLegislation = :typeLegislation, instanceLegislation = :instanceLegislation, dateLegislation = :dateLegislation, titleLegislation = :titleLegislation, descriptionLegislation = :descriptionLegislation WHERE idLegislation = :id";
              $execEditLegislation = $connection->prepare($queryEditLegislation);
              $execEditLegislation->execute(array(
                'id' => $_GET['id'],
                ':typeLegislation' => $dados['typeLegislation'],
                ':instanceLegislation' => $dados['instanceLegislation'],
                ':dateLegislation' => $dados['dateLegislation'],
                ':titleLegislation' => strtoupper($dados['titleLegislation']),
                ':descriptionLegislation' => nl2br($dados['descriptionLegislation']),
              ));
            } else {
              // Verifica se o arquivo existe
              if (file_exists($edit_legislation['fileLegislation'])) {
                // Apaga o arquivo
                unlink($edit_legislation['fileLegislation']);
              }
              $uploaddir = file_exists('upload/legislation/') ? 'upload/legislation/' : mkdir('upload/legislation/', 0777, true);
              $uploadfile = $uploaddir . basename($_FILES['fileLegislation']['name']);
              move_uploaded_file($_FILES['fileLegislation']['tmp_name'], $uploadfile);
              $queryEditLegislation = "UPDATE legislation SET typeLegislation = :typeLegislation, instanceLegislation = :instanceLegislation, dateLegislation = :dateLegislation, titleLegislation = :titleLegislation, descriptionLegislation = :descriptionLegislation, fileLegislation = :fileLegislation WHERE  idLegislation = :id";
              $execEditLegislation = $connection->prepare($queryEditLegislation);
              $execEditLegislation->execute(array(
                'id' => $id,
                ':typeLegislation' => $dados['typeLegislation'],
                ':instanceLegislation' => $dados['instanceLegislation'],
                ':dateLegislation' => $dados['dateLegislation'],
                ':titleLegislation' => strtoupper($dados['titleLegislation']),
                ':descriptionLegislation' => nl2br($dados['descriptionLegislation']),
                ':fileLegislation' => $uploadfile,
              ));
            }
            // Verifica se a consulta foi bem sucedida
            if ($execEditLegislation->rowCount() > 0) {
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
                  <div id="slWrapper" class="form-group mg-b-10-force parsley-select">
                    <label class="form-control-label">Tipo de Legislação: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" name="typeLegislation" data-placeholder="Selecione o Tipo " data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                      <?= isset($_GET['id']) ? '' : '<option selected dirname label="Selecione o Tipo"></option>' ?>
                      <option <?= $edit_legislation['typeLegislation'] == 'lei'  ? 'selected' : '' ?> value="lei">LEI</option>
                      <option <?= $edit_legislation['typeLegislation'] == 'decreto'  ? 'selected' : '' ?> value="decreto">DECRETO</option>
                      <option <?= $edit_legislation['typeLegislation'] == 'portaria'  ? 'selected' : '' ?> value="portaria">PORTARIA</option>
                      <option <?= $edit_legislation['typeLegislation'] == 'resoluções'  ? 'selected' : '' ?> value="resoluções">RESOLUÇÃO</option>
                      <option <?= $edit_legislation['typeLegislation'] == 'emenda constitucional'  ? 'selected' : '' ?> value="emenda constitucional">EMENDA CONSTITUCIONAL</option>
                    </select>
                    <div id="slErrorContainer"></div>
                  </div>
                </div><!-- col-3 -->
                <div class="col-lg-3">
                  <div id="slWrapper" class="form-group mg-b-10-force parsley-select">
                    <label class="form-control-label">Instância da Legislação: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" name="instanceLegislation" data-placeholder="Selecione a instância " data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                      <option <?= $edit_legislation['instanceLegislation'] == 'federal'  ? 'selected' : '' ?> value="federal">FEDERAL</option>
                      <option <?= $edit_legislation['instanceLegislation'] == 'estadual'  ? 'selected' : '' ?> value="estadual">ESTADUAL</option>
                    </select>
                    <div id="slErrorContainer"></div>
                  </div>
                </div><!-- col-3 -->
                <div class="col-lg-2">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Data da Legislação: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="date" name="dateLegislation" value="<?= @$edit_legislation['dateLegislation']; ?>" required>
                  </div>
                </div><!-- col-2 -->
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">Título da Legislação: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="titleLegislation" value="<?= @$edit_legislation['titleLegislation']; ?>" placeholder="Informe o Título da Legislação" required>
                  </div>
                </div><!-- col-4 -->
                <div class="col-lg-12">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Descrição da Legislação: <span class="tx-danger">*</span></label>
                    <textarea rows="5" class="form-control" name="descriptionLegislation" placeholder="Descreva de que trata a legislação" required><?= nl2br(@$edit_legislation['descriptionLegislation']); ?></textarea>
                  </div>
                </div><!-- col-12 -->
                <div class="col-lg-12">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Arquivo da Legislação: <span class="tx-danger">*</span></label>
                    <input type="file" class="form-control" id="fileLegislation" name="fileLegislation" <?= !isset($_GET['id']) ? 'required' : '' ?>>
                  </div>
                </div><!-- col-12 -->
          </form>
        </div><!-- row -->

        <div class="form-layout-footer">
          <?php
          if (isset($_GET['id'])) {
          ?>
            <button type="submit" name="editLegislation" class="btn btn-primary mg-r-5 rounded">
              <span class="mdi mdi-content-save-edit-outline tx-20"></span>
              Salvar Edição
            </button>
          <?php } else { ?>
            <button type="submit" name="saveLegislation" class="btn btn-primary mg-r-5 rounded">
              <i class="mdi mdi-content-save-check-outline tx-20"></i>
              Salvar Dados
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
