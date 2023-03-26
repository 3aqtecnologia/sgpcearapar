<?php
date_default_timezone_set('America/Fortaleza');
// require dirname(__DIR__, 1) . '/vendor/autoload.php';

//require '../db/connection.php';
require '../util/util.php';
require '../util/outfunc.php';

//$connection = novaConexao();



/*$sql = "SELECT * FROM person
        INNER JOIN projects
        ON person.id = projects.id_person_client
        INNER JOIN types_project
        ON projects.id_type_project = types_project.id
        WHERE projects.uuid = '{$_GET['id']}'";
$sql = "SELECT * FROM vw_proposal
        WHERE uuid = '{$_GET['id']}'";
$result = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$refer = $result[0]; */

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
      background-image: url('<?= dirname(__DIR__, 1) ?>/pdfs/top.png');
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
      width: 80%;
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

<body style="background-image: url(<?= dirname(__DIR__, 1) ?>/pdfs/logo_vert.png); background-repeat: no-repeat;background-position:right bottom; background-size: auto;">
  <div id="header">

  </div>
  <div id="footer">
    <p class="page">
      <?= uuidv4() ?>
    </p>
  </div>

  <div id="content">

    <small class="title">
      CERTIDÃO DE PUBLICAÇÃO DE DOCUMENTOS NO SÍTIO ELETRÔNICO DA COMPANHIA
    </small>

    <svg width="400" height="110">
      <rect width="300" height="100" style="fill:rgb(0,0,255);stroke-width:3;stroke:rgb(0,0,0)" />
    </svg>

    <p style="text-align: justify;">
      Certifico, para os devidos fins, que os documentos abaixo relacionados foram devidamente
      publicados no sítio eletrônico desta companhia na data de <?= dataExtenso() ?>.
      <br />
    </p>

    <span class="dateDoc">
      <?= dataExtenso("Fortaleza/CE, ", false, "2022-2-24"); ?>
    </span>

  </div>

</body>

</html>
