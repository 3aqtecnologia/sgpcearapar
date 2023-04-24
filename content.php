<?php
//switch ($_SESSION['NIVEL']) {
//    Sertor Administrativo
//case 1:
if (!isset($_GET['page']) || $_GET['page'] == null) {
  include "app/pages/initChart.php";
} else {
  switch (@$_GET['page']) {
    case 'inicio':
      include "app/pages/init.php";
      break;

      #users
    case 'users':
      include "app/pages/users.php";
      break;
    case 'user':
      include "app/pages/user.php";
      break;

      #ordinances
    case 'ordinances':
      include "app/pages/ordinances.php";
      break;
    case 'ordinance':
      include "app/pages/ordinance.php";
      break;
    case 'managerOrdinances':
      include "app/pages/managerOrdinances.php";
      break;

      #legislations
    case 'legislations':
      include "app/pages/legislations.php";
      break;
    case 'legislation':
      include "app/pages/legislation.php";
      break;


      #Quando não encontrar pagina
    default:
      include "app/pages/page-notfound.php";
      break;
  }
}
