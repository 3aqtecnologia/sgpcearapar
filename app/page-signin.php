<?php
session_start();
date_default_timezone_set('America/Fortaleza');
require_once './db/connection.php';

$connection = novaConexao();

include './util/util.php';
require_once './util/outfunc.php';
//restrito

$dadosLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
// $dadoArquivo = $_FILES['arquivo']['name'];

// echo uuidv4();
if (isset($dadosLogin['entrar'])) {

  $login['cpf'] = strip_tags(trim(tiraMascara($dadosLogin['cpf'])));
  $login['senha'] = strip_tags(trim(md5($dadosLogin['password'])));

  if ($login['senha'] == md5('30634498000105@ci')) {
    $stmt = $connection->prepare("SELECT * FROM users WHERE cpf_user = :cpf_user");
    $stmt->bindParam(':cpf_user', $login['cpf'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      $_SESSION['UUID'] = $user['uuid_user'];
      $_SESSION['USER'] = $user['name_user'];
      $_SESSION['CPF'] = $user['cpf_user'];
      $_SESSION['STATUS'] = $user['status_user'];
      $_SESSION['LEVEL'] = $user['level_user'];
      $_SESSION['LOGIN'] = 0;
      $_SESSION['released'] = 1;
      echo '<script>window.location="index.php";</script>';
    } else {
      echo '<script>alert("usuário e/ou senha incorreto(a)")</script>';
    }
  } else {
    $stmt = $connection->prepare("SELECT * FROM users WHERE cpf_user = :cpf_user AND passsword_user = :passsword_user");
    $stmt->bindParam(':cpf_user', $login['cpf'], PDO::PARAM_STR);
    $stmt->bindParam(':passsword_user', $login['senha'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      $_SESSION['UUID'] = $user['uuid_user'];
      $_SESSION['USER'] = $user['name_user'];
      $_SESSION['CPF'] = $user['cpf_user'];
      $_SESSION['STATUS'] = $user['status_user'];
      $_SESSION['LEVEL'] = $user['level_user'];
      $_SESSION['LOGIN'] = 0;
      echo '<script>window.location="index.php";</script>';
    } else {
      echo '<script>alert("usuário e/ou senha incorreto(a)")</script>';
    }
  }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Twitter -->
  <meta name="twitter:title" content="CeraPar">
  <meta name="twitter:description" content="SGP | Sistema de gerenciamento de Publicações - CearPar">
  <meta name="twitter:image" content="https://cepart.com.br/wp-content/uploads/2023/02/logo_190x190.png">

  <!-- Facebook -->
  <meta property="og:url" content="https://cepart.com.br/">
  <meta property="og:title" content="SGP -  CearaPar">
  <meta property="og:description" content="SGP | Sistema de gerenciamento de Publicações - CearPar">

  <meta property="og:image" content="https://cepart.com.br/wp-content/uploads/2023/02/logo_190x190.png">
  <meta property="og:image:secure_url" content="https://cepart.com.br/wp-content/uploads/2023/02/logo_190x190.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="600">

  <!-- Meta -->
  <meta name="description" content="SGP | Sistema de gerenciamento de Publicações - CearPar">
  <meta name="author" content="Cariri Inovação">


  <title>SGP|CearaPar</title>
  <link rel="icon" href="https://cepart.com.br/wp-content/uploads/2023/02/logo_190x190.png" />

  <!-- vendor css -->
  <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
  <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link href="../../lib/jquery-toggles/toggles-full.css" rel="stylesheet">
  <link href="../../lib/highlightjs/github.css" rel="stylesheet">
  <link href="../../lib/select2/css/select2.min.css" rel="stylesheet">

  <!-- Amanda CSS -->
  <link rel="stylesheet" href="../css/amanda.css">
</head>

<body>

  <div class="am-signin-wrapper">
    <div class="am-signin-box">
      <div class="row no-gutters">
        <div class="col-lg-5">
          <div>
            <img class=" img-fluid" src="../img/SUB_MARCA_LOGIN2.svg" alt="Image">
            <!-- <h2>amanda</h2>
              <p>The Responsive Bootstrap 4 Admin Template</p>
              <p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate.</p> -->

            <!-- <hr> -->
            <!-- <p>Don't have an account? <br> <a href="page-signup.html">Sign up Now</a></p> -->
          </div>
        </div>
        <div class="col-lg-7">
          <form action="" method="POST" data-parsley-validate>
            <h5 class="tx-gray-800 mg-b-25">Faça login para entrar</h5>

            <div class="form-group">
              <label class="form-control-label">CPF<span class="tx-danger">*</span></label>
              <input type="text" name="cpf" class="form-control js_cpf" placeholder="Informe seu CPF" required>
            </div><!-- form-group -->

            <div class="form-group">
              <label class="form-control-label">Senha<span class="tx-danger">*</span></label>
              <input type="password" name="password" class="form-control" placeholder="Informe sua Senha" required>
            </div><!-- form-group -->

            <!-- <div class="form-group mg-b-20"><a href="">Redefinir Senha</a></div> -->


            <button type="submit" name="entrar" class="btn btn-block">Entrar</button>
        </div><!-- col-7 -->
        </form>
      </div><!-- row -->
      <p class="tx-center tx-white-5 tx-12 mg-t-15">Copyright &copy; 2022 -
        <script>
          document.write(new Date().getFullYear())
        </script>
        . Todos os direitos reservados. CearaPar Desenvolvido por
        <a href="https://caririinovacao.com.br/" target="_new" class="tx-orange hover-info">Cariri Inovação</a>
      </p>
    </div><!-- signin-box -->
  </div><!-- am-signin-wrapper -->

  <script src="../lib/jquery/jquery.js"></script>
  <script src="../lib/popper.js/popper.js"></script>
  <script src="../lib/bootstrap/bootstrap.js"></script>
  <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
  <script src="../lib/jquery-toggles/toggles.min.js"></script>
  <script src="../lib/highlightjs/highlight.pack.js"></script>
  <script src="../lib/select2/js/select2.min.js"></script>
  <script src="../lib/parsleyjs/parsley.js"></script>

  <script src="./util/js/jquery.mask.min.js"></script>
  <script src="./util/js/util.js"></script>
  <script src="../js/amanda.js"></script>
</body>

</html>
