<?php
session_start();
// !isset( $_SESSION[ 'released' ] ) ? header( 'Location: page-signin.php' ) : '';
//Define informações locais
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

require_once './db/connection.php';
$connection = novaConexao();

include './util/util.php';
require_once './util/outfunc.php';

$d = explode('/', $_SERVER['PHP_SELF']);
$cd = count($d) - 2;
$ld = $d[$cd] . '/';

if (!isset($_SESSION['USER'])) {
    header('Location: page-signin.php');
} else if (isset($_GET['acao']) && $_GET['acao'] === 'sair') {
    session_destroy();
    header('Location: page-signin.php');
}

?>
<!DOCTYPE html>
<html lang='pt_BR'>

<head>
    <!-- Required meta tags -->
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />

    <!-- Twitter -->
    <meta name='twitter:title' content='CeraPar'>
    <meta name='twitter:description' content='Sistema de gerenciamento de Publicações - CearPar'>
    <meta name='twitter:image' content='https://cepart.com.br/wp-content/uploads/2023/02/logo_190x190.png'>

    <!-- Facebook -->
    <meta property='og:url' content='https://cepart.com.br/'>
    <meta property='og:title' content='SGP|CearaPar'>
    <meta property='og:description' content='Sistema de gerenciamento de Publicações - CearPar'>

    <meta property='og:image' content='https://cepart.com.br/wp-content/uploads/2023/02/logo_190x190.png'>
    <meta property='og:image:secure_url' content='https://cepart.com.br/wp-content/uploads/2023/02/logo_190x190.png'>
    <meta property='og:image:type' content='image/png'>
    <meta property='og:image:width' content='1200'>
    <meta property='og:image:height' content='600'>

    <!-- Meta -->
    <meta name='description' content='CearaPar' />
    <meta name='author' content='Cariri Inovação' />

    <title>SGP|CearaPar</title>
    <link rel='icon' href='../img/SIMBOLO.png' />

    <!-- vendor css -->
    <script src='../lib/jquery/jquery.js'></script>
    <link href='../lib/font-awesome/css/font-awesome.css' rel='stylesheet' />
    <link href='../lib/Ionicons/css/ionicons.css' rel='stylesheet' />
    <link href='../lib/perfect-scrollbar/css/perfect-scrollbar.css' rel='stylesheet' />
    <link href='../lib/jquery-toggles/toggles-full.css' rel='stylesheet' />
    <link href='../lib/rickshaw/rickshaw.min.css' rel='stylesheet' />
    <link href='../lib/highlightjs/github.css' rel='stylesheet'>
    <link href='../lib/datatables/jquery.dataTables.css' rel='stylesheet'>
    <link href='../lib/select2/css/select2.min.css' rel='stylesheet'>
    <link href='../lib/spectrum/spectrum.css' rel='stylesheet'>

    <link rel='stylesheet' href='https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css'>

    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css' />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@mdi/light-font@0.2.63/css/materialdesignicons-light.min.css' />

    <!-- Amanda CSS -->
    <link rel='stylesheet' href='../css/amanda.css' />

</head>

<body>
    <div class='am-header'>
        <div class='am-header-left'>
            <a id='naviconLeft' href='' class='am-navicon d-none d-lg-flex'><i class='icon ion-navicon-round'></i></a>
            <a id='naviconLeftMobile' href='' class='am-navicon d-lg-none'><i class='icon ion-navicon-round'></i></a>
            <a href='index.php' class='am-logo'>
                <img class='img-fluid' src='../img/MARCA_PRINCIPAL2.svg' alt='Image' />
            </a>
        </div>
        <!-- am-header-left -->

        <div class='am-header-right'>
            <div id='notifications'></div>
            <!-- dropdown -->
            <div class='dropdown dropdown-profile'>
                <a href='' class='nav-link nav-link-profile' data-toggle='dropdown'>
                    <img src='../img/img11.jpg' class='wd-32 rounded-circle' alt='' />
                    <span class='logged-name'><span class='hidden-xs-down'>
                            <?php echo strtoupper(explode(' ',  $_SESSION['USER'])[0] . ' ' . explode(' ',  $_SESSION['USER'])[1]) ?>
                        </span>
                        <i class='fa fa-angle-down mg-l-3'></i></span>
                </a>
                <div class='dropdown-menu wd-200'>
                    <ul class='list-unstyled user-profile-nav'>
                        <li>
                            <a href=''><i class='icon ion-ios-person-outline'></i> Meus Dados</a>
                        </li>
                        <!-- <li>
<a href = ''
><i class = 'icon ion-ios-gear-outline'></i> Settings</a
>
</li>
<li>
<a href = ''
><i class = 'icon ion-ios-download-outline'></i> Downloads</a
>
</li>
<li>
<a href = ''
><i class = 'icon ion-ios-star-outline'></i> Favorites</a
>
</li>
<li>
<a href = ''
><i class = 'icon ion-ios-folder-outline'></i> Collections</a
>
</li> -->
                        <li>
                            <a href='?acao=sair'><i class='icon ion-power'></i> Sair</a>
                        </li>
                    </ul>
                </div>
                <!-- dropdown-menu -->
            </div>
            <!-- dropdown -->
        </div>
        <!-- am-header-right -->
    </div>
    <!-- am-header -->

    <div class='am-sideleft'>
        <?php require_once('./includes/sideleft.php');
        ?>
    </div>
    <!-- am-sideleft -->

    <div class='am-mainpanel'>
        <div class='am-pagetitle'>
            <h4 class='am-title'>
                <?php
                $title = match ($_GET['page']) {
                    'inicio' => 'Dashboard',
                    'users' => 'Usuários dos Sistema',
                    'user' => 'Usuários dos Sistema',
                    'profile' => 'Usuário',
                    'ordinances' => 'Portarias',
                    'ordinance' => 'Portarias',
                    'legislations' => 'Legislação',
                    default => 'Dashboard'
                };
                ?>
                <?= $title ?> -
                <?php
                setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                date_default_timezone_set('America/Sao_Paulo');
                echo strftime('%A, %d de %B de %Y', strtotime('today'));


                ?>
            </h4>
            <!-- <form id = 'searchBar' class = 'search-bar' action = 'index.html'>
<div class = 'form-control-wrapper'>
<input type = 'search' class = 'form-control bd-0' placeholder = 'Search...'>
</div> -->
            <!-- form-control-wrapper -->
            <!-- <button id = 'searchBtn' class = 'btn btn-orange'><i class = 'fa fa-search'></i></button>
</form> -->
            <!-- search-bar -->
        </div>
        <!-- am-pagetitle -->

        <div class='am-pagebody'>
            <?php require_once('../content.php');
            ?>
        </div>
        <!-- am-pagebody -->
        <div class='am-footer'>
            <span>Copyright &copy;
                2022 -
                <script>
                    document.write(new Date().getFullYear());
                </script>
                Todos os direitos reservados. CearaPar | Gestão de Ativos
            </span>
            <span>
                Desenvolvido por
                <a href='https://caririinovacao.com.br/' target='_new' class='tx-orange hover-info'>Cariri
                    Inovação</a>
            </span>
        </div>
        <!-- am-footer -->
    </div>
    <!-- am-mainpanel -->

    <script src='../lib/jquery/jquery.js'></script>
    <script src='../lib/popper.js/popper.js'></script>
    <script src='../lib/bootstrap/bootstrap.js'></script>
    <script src='../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js'></script>
    <script src='../lib/jquery-toggles/toggles.min.js'></script>
    <script src='../lib/d3/d3.js'></script>
    <script src='../lib/rickshaw/rickshaw.min.js'></script>
    <script src='http://maps.google.com/maps/api/js?key=AIzaSyAEt_DBLTknLexNbTVwbXyq2HSf2UbRBU8'>
    </script>
    <script src='../lib/gmaps/gmaps.js'></script>
    <script src='../lib/Flot/jquery.flot.js'></script>
    <script src='../lib/Flot/jquery.flot.pie.js'></script>
    <script src='../lib/Flot/jquery.flot.resize.js'></script>
    <script src='../lib/flot-spline/jquery.flot.spline.js'></script>
    <script src='../lib/highlightjs/highlight.pack.js'></script>
    <script src='../lib/select2/js/select2.min.js'></script>
    <script src='../lib/parsleyjs/parsley.js'></script>
    <script src='../lib/spectrum/spectrum.js'></script>

    <script src='./util/js/jquery.mask.min.js'></script>
    <!-- datatables -->
    <script src='../lib/datatables/jquery.dataTables.js'></script>
    <script src='../lib/datatables-responsive/dataTables.responsive.js'></script>

    <script src='https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js'></script>

    <script src='../js/amanda.js'></script>
    <script src='../js/dashboard.js'></script>
    <script src='../js/ResizeSensor.js'></script>
    <script src='./util/js/util.js'></script>

</body>

</html>
