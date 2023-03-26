<?php
//Define informações locais
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');


$stmt = $connection->prepare("SELECT * FROM legislation ORDER BY dateLegislation DESC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump(dirname(__DIR__, 1));
?>

<div class="card pd-20 pd-sm-40">
  <h6 class="card-body-title">Leis e Decretos </h6>
  <p class="mg-b-20 mg-sm-b-30">
    <a href="?page=legislation&nav=1" class="btn btn-outline-primary border-0">
      <span class="mdil mdil-plus tx-20 "></span> Nava Legislação
    </a>
  </p>

  <div class="table-wrapper">
    <table id="datatable1" class="table display responsive  table-primary table-hover">
      <thead>
        <tr>
          <th scope="col" class="wd-5p text-center">TIPO</th>
          <th scope="col" class="wd-10p text-center">INSTÂNCIA</th>
          <th scope="col" class="wd-5p text-center">Nº DA LEGISLAÇÃO</th>
          <th scope="col" class="wd-10p text-center">DATA DA LEGISLAÇÃO</th>
          <th scope="col" class="wd-40p text-center">DESCRIÇÃO DA LEGISLAÇÃO</th>
          <th scope="col" class="wd-10p text-center">
            <span class="d-none d-sm-block mdil mdil-settings tx-18"></span>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result as $row) { ?>

          <tr scope="row">
            <td class="align-center align-middle">
              <?= strtoupper($row['typeLegislation']) ?>
            </td>

            <td class="text-center  align-middle">
              <?= strtoupper($row['instanceLegislation']) ?>
            </td>
            <td class="text-center  align-middle">
              <?= number_format($row['nuberLegislation'], 0, '', '.') ?>
            </td>
            <td class="text-center  align-middle">

              <?= strtolower(dataExtensoDB('', $row['dateLegislation'])); ?>
            </td>
            <td class="wrapper align-middle ">
              <?= nl2br($row['descriptionLegislation']) ?>
            </td>

            <td class="text-center  align-middle">
              <!-- <a href="#" class="btn btn-outline-primary btn-icon rounded-circle border-0" data-toggle="tooltip" data-placement="top" title="Editar Usuário">
                <div><i class="mdil mdil-pencil tx-24"></i></div>
              </a> -->
              <div class="btn-group" role="group" aria-label="Basic example">
                <a href="<?= $ld . '../' . $row['fileLegislation'] ?>" target="_new" class="btn btn-outline-primary border-0" data-toggle="tooltip" data-placement="top" title="Download do Documento">
                  <!-- <i class="mdil mdil-pencil tx-26 tx-md-18"></i> -->
                  <span class="mdi mdi-file-download-outline tx-26 tx-md-20"></span>
                </a>

                <a href=">" target="_new" class="btn btn-outline-primary border-0" data-toggle="tooltip" data-placement="top" title="Editar Dados">
                  <!-- <i class="mdil mdil-pencil tx-26 tx-md-18"></i> -->
                  <span class="mdi mdi-pencil-outline tx-26 tx-md-20"></span>
                </a>

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
      // order: [4, 'desc'],
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
