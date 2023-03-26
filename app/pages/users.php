<?php
$stmt = $connection->prepare("SELECT * FROM users WHERE level_user >= 1 ");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($result);
?>

<div class="card pd-20 pd-sm-40">
  <h6 class="card-body-title">Lista de usuários do Sistema</h6>
  <p class="mg-b-20 mg-sm-b-30">
    <a href="?page=user&settings=1" class="btn btn-outline-primary text-orange">
      <span class="mdil mdil-plus tx-20 "></span> Novo Usuário
    </a>
  </p>

  <div class="table-wrapper">
    <table id="datatable1" class="table display responsive nowrap table-primary table-hover">
      <thead>
        <tr>
          <th scope="col" class="wd-30p">Nome</th>
          <th scope="col" class="wd-10p text-center">CPF</th>
          <th scope="col" class="wd-10p text-center">Status</th>
          <th scope="col" class="wd-10p text-center">Nível de Acesso</th>
          <th scope="col" class="wd-5p text-center">
            <span class="d-none d-sm-block mdil mdil-settings tx-18"></span>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result as $row) { ?>

          <tr scope="row">
            <td class="align-center align-middle">
              <?= $row['name_user'] ?>
            </td>
            <td class="text-center  align-middle">
              <?= MascaraCPF($row['cpf_user']) ?>
            </td>
            <td class="text-center  align-middle">
              <?= $row['status_user'] == '1' ? '<span class="mdi mdi-account tx-18 text-success" data-toggle="tooltip" data-placement="top" title="Usuário Ativo"></span>' : '<span class="mdi mdi-account-cancel tx-18 text-danger" data-toggle="tooltip" data-placement="top" title="Usuário Inativo"></span>' ?>
            </td>
            <td class="text-center  align-middle">
              <?php
              $levelUser = match ($row['level_user']) {
                0 => 'Master',
                1 => 'Admistrador',
                2 => 'Editor',
                3 => 'Revisor',
              };
              echo $levelUser; ?>
            </td>
            <td>
              <!-- <a href="#" class="btn btn-outline-primary btn-icon rounded-circle border-0" data-toggle="tooltip" data-placement="top" title="Editar Usuário">
                <div><i class="mdil mdil-pencil tx-24"></i></div>
              </a> -->
              <div class="btn-group" role="group" aria-label="Basic example">
                <a href="?page=user&settings=1&id=<?= $row['uuid_user'] ?>" type="button" class="btn btn-outline-primary border-0" data-toggle="tooltip" data-placement="top" title="Editar Usuário">
                  <span class="mdi mdi-account-edit-outline tx-26 tx-md-20"></span>
                </a>
                <!-- <a href="?page=user&settings=1&id=<? $row['uuid_user'] ?>" type="button" class="btn btn-outline-primary border-0" data-toggle="tooltip" data-placement="top" title="Resetar Senha do  Usuário">
                  <span class="mdi mdi-shield-key-outlinetx-26 tx-md-20"></span>
                </a> -->
                <!-- <button type="button" class="btn btn-outline-primary border-0" data-toggle="tooltip" data-placement="top" title="Visualizar Usuário">
                  <i class="mdil mdil-eye tx-26 tx-md-18"></i>
                </button> -->
              </div>

            </td>
          </tr>
        <?php } ?>

      </tbody>
    </table>
  </div><!-- table-wrapper -->
</div><!-- card -->

<script src="../lib/jquery/jquery.js"></script>

<script>
  $(function() {
    'use strict';

    $('#datatable1').DataTable({
      responsive: true,
      order: [0, 'desc'],

      // dom: 'Bfrtip',
      // "buttons": [
      //   'excel', 'pdf'
      // ],
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
