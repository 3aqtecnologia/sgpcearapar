<?php
date_default_timezone_set('America/Fortaleza');
// require dirname(__DIR__, 1) . '/vendor/autoload.php';

require '../db/connection.php';
require '../util/util.php';
require '../util/outfunc.php';

$connection = novaConexao();



/*$sql = "SELECT * FROM person
        INNER JOIN projects
        ON person.id = projects.id_person_client
        INNER JOIN types_project
        ON projects.id_type_project = types_project.id
        WHERE projects.uuid = '{$_GET['id']}'";*/
$sql = "SELECT * FROM docsInst
        WHERE codeDocs = '{$_GET['code']}'";
$result = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$refer = $result[0];

echo "<pre>";
//print_r($refer);
echo "</pre>";

/*
$sql = "SELECT * FROM person
        INNER JOIN projects
        ON person.id = projects.id_person_client
        WHERE person.id = '{$_GET['idcliente']}'";
$result = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$uuid = $_GET['uuid'];
$sql = "SELECT * FROM projects WHERE uuid = '112abf8b-6343-46ce-8ab3-1f584e2e6c71'";
$result = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$refer = $result[0];
* *************************** */
?>
<html>

<head>

  <style>
    @font-face {
      font-family: 'Poppins';
      font-style: normal;
      font-weight: normal;
      src: url(https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;400;500;600&display=swap);
    }

    @page {
      margin: 110px 0px;
    }

    #header {
      position: fixed;
      left: 0px;
      top: -110px;
      right: 0px;
      height: 300px;
      background-image: url('<?= dirname(__DIR__, 1) ?>/pdfs/top2.png');
      background-repeat: no-repeat;
      background-size: contain;
      background-position: center top;
      z-index: -1;
    }

    #footer {
      position: fixed;
      left: 0px;
      bottom: -110px;
      right: 0px;
      height: 120px;
      opacity: 1;
      background-image: url('<?= dirname(__DIR__, 1) ?>/pdfs/base.png');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center top;
      z-index: -1;
      color: #939393;
      font-size: 0.5rem;
      padding-left: 1rem;
      letter-spacing: 2px;
      font-family: 'Poppins';

    }

    #footer .page:after {
      margin-left: 98%;
      padding: 5px;
      content: counter(page, georgian);

    }

    #content {
      position: relative;
      top: 140px;
      left: 12.5%;
      width: 78%;
      color: #2E2E2E;
      font-weight: lighter;
      font-size: .8rem;
      font-family: 'Poppins';

    }

    .title {
      font-size: 1rem;
      color: #003333;
      margin-top: 2.2rem;
      text-align: justify;
      font-weight: bold;
    }

    .title_p1 {
      font-size: 15px;
      color: #212761;
      margin-top: 4rem;
      text-align: right;
    }

    .dtCapa {
      position: fixed;
      bottom: 5px;
      left: 30%;
      text-align: center;
      color: darkorange;
    }

    #table_material tbody tr:nth-child(2n+2) {
      background: #FFE4B5;
      opacity: 0.4;
    }

    .dateDoc {
      position: absolute;
      right: .1%;
      top: 7rem;
    }
  </style>

<body style="background-image: url(<?= dirname(__DIR__, 1) ?>/pdfs/logo_vert.png); background-repeat: no-repeat;background-position:top right; background-size: cover;">
  <div id="header">

  </div>
  <div id="footer">
    <p class="page">
      <?= $refer['codeDocs'] ?>
    </p>
  </div>
  <?php
  if ($refer == '') {
    echo '<h2 style="text-transform: uppercase; margin-top:10rem; margin-left:25%; color:#003333;">Publicação não Encontrada</h2>';
  } else { ?>
    <div id="content">
      <small class="title">
        CERTIDÃO DE PUBLICAÇÃO DE DOCUMENTOS NO SÍTIO ELETRÔNICO DA COMPANHIA
      </small>
      <svg width="400" height="110">
        <rect width="300" height="100" style="fill:rgb(0,0,255);stroke-width:3;stroke:rgb(0,0,0)" />
      </svg>
      <p style="text-align: justify; font-size:1.1em;">
        Certificamos, para os devidos fins, que o(s) documento(s) abaixo relacionado(s) foi(ram) devidamente publicado(s) no sítio eletrônico desta companhia na data de <?= dataExtenso('', '', $refer['created_at']) . " às " . date("H:i:s", strtotime($refer['created_at'])); ?>.
        <br />
      </p>
      <div style="margin-top:2rem;  font-size:.8em;">
        <table border="1" cellspacing="0" cellpadding="10" width="100%" class="" style="color: var(--primary)">
          <thead>
            <tr class="text-center" style="color: #fff; background-color: #003333;">
              <th class="text-center text-uppercase" style="text-transform: uppercase;" scope="col">Tipo do Documento</th>
              <th class="text-center text-uppercase" style="text-transform: uppercase;" scope="col">Titulo do Documento</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-center">
              <td class="text-center text-uppercase" style="text-transform: uppercase;"><?= $refer['tipoDoc'] ?></td>
              <td class="text-center text-uppercase" style="text-transform: uppercase;"><?= $refer['tituloDoc'] ?></td>
            </tr>
          </tbody>
        </table>
        <table border="1" cellspacing="0" cellpadding="10" width="100%" class="" style="color: var(--primary)">
          <thead>
            <tr class="text-center text-uppercase" style="color: #fff; background-color: #003333;">
              <th class="text-center text-uppercase" style="text-transform: uppercase;" scope="col">Arquivo disponivel em:</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-center">
              <td class="text-center" scope="row">
                https://cepart.com.br/
                <?php
                // $file = explode('/', $refer['arquivo']);
                ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div style="margin-top:1rem;">
        <span style="color:#003333; font-weight: bold; text-transform: uppercase; font-size:.8.5em;""> Código de Publicação:</span>  <?= $refer['codeDocs'] ?>
    </div>
          <span style=" float: right; margin-top: 2rem;">
          <?= dataExtenso("Fortaleza/CE, "); ?>
        </span>
      </div>
    <?php } ?>
</body>

</html>
