        <?php
        if (isset($_GET['id'])) {
          $id = $_GET['id'];
          $stmt = $connection->prepare("SELECT * FROM type_ordinance WHERE id_type_ordinance = :id");
          $stmt->bindParam(':id', $id);
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $type_ordinance = $result[0];
        }
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // echo '<pre>';
        // var_dump($dados);
        // echo '</pre>';

        if (isset($dados['savetypeOrdinance'])) {
          try {
            $querySaveTypeOrdinance = "INSERT INTO type_ordinance (title_type_ordinance) VALUES(:title_type_ordinance)";
            $cadTypeOrdinace = $connection->prepare($querySaveTypeOrdinance);
            $cadTypeOrdinace->bindParam(':title_type_ordinance', trim(strtoupper($dados['title_type_ordinance'])));
            $cadTypeOrdinace->execute();

            if ($cadTypeOrdinace->rowCount()) {
              echo '<script>alert("Tipo da Portária cadastrado com sucesso!");</script>';
            } else {
              echo '<script>alert("Erro ao Cadastrar o tipo, por favor tente novamente "); </script>';
            }
          } catch (PDOException $erro) {
            echo "<script>alert('Erro ao publicar portaria, por favor tente novamente -'$erro); </script>";
          }
        } elseif (isset($dados['editTypeOrdinance'])) {
          // echo '<pre>';
          // var_dump($dados);
          // echo '</pre>';
          try {

            $queryEditTypeOrdinance = "UPDATE type_ordinance SET title_type_ordinance = :title_type_ordinance WHERE id_type_ordinance = :id";
            $execEditTypeOrdinance = $connection->prepare($queryEditTypeOrdinance);
            $execEditTypeOrdinance->execute(array(
              ':id' => $id,
              ':title_type_ordinance' => $dados['title_type_ordinance']
            ));

            // Verifica se a consulta foi bem sucedida
            if ($execEditTypeOrdinance->rowCount() > 0) {
        ?>
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
              </div><!-- alert -->
              <!-- <script>
                sleep(2);
                history.go(-2);
              </script> -->
            <?php

            } else { ?>
              <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <div class="d-flex align-items-center justify-content-start">
                  <i class="icon ion-ios-close alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
                  <span>Não foi possível atualizar o registro.</span>
                </div><!-- d-flex -->
              </div><!-- alert -->
        <?php }
            // if ($execEditTypeOrdinance->rowCount() > 0) {
            //   echo '<script>alert("Registro atualizado com sucesso!");</script>';
            //   // Redirecionar para a página principal
            //   header('Location: index.php?page=managerOrdinances&settings=1');
            //   exit;
            // } else {
            //   echo '<script>alert("Não foi possível atualizar o registro, por favor tente novamente "); </script>';
            // }
          } catch (PDOException $erro) {
            echo "<script>alert('Erro ao Atualizar os dados do usuários, tente novamente'. $erro); </script>";
          }
        }

        ?>
        <div class="card pd-20 pd-sm-40">
          <h6 class="card-body-title text-primary">
            <span class="mdil mdil-tag tx-20"></span>
            &nbsp;Tipo de Portária
          </h6>
          <p class="mg-b-20 mg-sm-b-30">
            Cadastre um tipo de portária.
          </p>
          <form action="" method="POST" enctype="multipart/form-data" data-parsley-validate>
            <div class="form-layout">
              <div class="row mg-b-25">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label class="form-control-label">Tipo de Portária: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="title_type_ordinance" value="<?= $type_ordinance['title_type_ordinance'] ?>" placeholder="Informe o Tipo da Portaria" required>
                  </div>

          </form>
        </div><!-- row -->

        <div class="form-layout-footer">
          <?php
          if (isset($_GET['id'])) {
          ?>
            <button type="submit" name="editTypeOrdinance" class="btn btn-primary mg-r-5 rounded">
              <span class="mdi mdi-content-save-edit-outline tx-20"></span>
              Salvar Edição
            </button>
          <?php } else { ?>
            <button type="submit" name="savetypeOrdinance" class="btn btn-primary mg-r-5 rounded">
              <i class="mdi mdi-content-save-check-outline tx-20"></i>
              Gravar
            </button>
          <?php } ?>
          <button type="reset" class="btn btn-outline-danger border-0 rounded">
            <span class="mdi mdi-trash-can-outline tx-20"></span>
            Cancelar
          </button>
        </div><!-- form-layout-footer -->
        </div><!-- form-layout -->
        <div class="row">
          <?php
          $stmt = $connection->prepare("SELECT * FROM type_ordinance ORDER BY title_type_ordinance ASC");
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          // var_dump(dirname(__DIR__, 1));
          ?>
          <div class="col-lg-12">
            <div class="card pd-20 pd-sm-40">
              <h6 class="card-body-title">Lista de Portarias Publicadas</h6>

              <div class="table-wrapper">
                <table id="datatable1" class="table display responsive  table-primary table-hover">
                  <thead>
                    <tr>
                      <th scope="col" class="wd-5p text-center">CÓDIGO</th>
                      <th scope="col" class="wd-40p text-center">DESCRIÇÃO DO TIPO DE PORTARIA</th>
                      <th scope="col" class="wd-10p text-center">
                        <span class="d-none d-sm-block mdil mdil-settings tx-18"></span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $SEQ = 0;
                    foreach ($result as $row) {
                      $SEQ++;
                    ?>
                      <tr scope="row">
                        <td class="wrapper align-middle ">

                          <?= str_pad($SEQ, 3, "0", STR_PAD_LEFT) ?>
                        </td>
                        <td class="wrapper align-middle ">
                          <?= nl2br($row['title_type_ordinance']) ?>
                        </td>

                        <td class="text-center  align-middle">

                          <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="?page=managerOrdinances&settings=1&id=<?= $row['id_type_ordinance'] ?>" target="_new" class="btn btn-outline-primary border-0" data-toggle="tooltip" data-placement="top" title="Editar Dados">
                              <!-- <i class="mdil mdil-pencil tx-26 tx-md-18"></i> -->
                              <span class="mdi mdi-pencil-outline tx-26 tx-md-20"></span>
                            </a>
                          </div>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div><!-- table-wrapper -->
            </div><!-- card -->
          </div>
        </div>
        </div><!-- card -->

        <script src="../lib/jquery/jquery.js"></script>
        <script>
          $(function() {
            'use strict';

            $('#datatable1').DataTable({
              responsive: true,
              order: [3, 'desc'],
              // "ordering": false,
              language: {
                // url: "https://cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json",
                searchPlaceholder: 'Buscar...',
                sSearch: '',
                "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                lengthMenu: '_MENU_ resultados por página',
                infoEmpty: "Nenhum registro disponível",
                infoFiltered: "(filtrado de _MAX_ registros no total)",
                zeroRecords: "Nenhum registro encontrado",
                "paginate": {
                  "next": "Próximo",
                  "previous": "Anterior",
                  "first": "Primeiro",
                  "last": "Último"
                },
              }
            });

            $('#datatable2').DataTable({
              bLengthChange: false,
              searching: false,
              responsive: true
            });

            // Select2
            $('.dataTables_length select').select2({
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
          });
        </script>
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
