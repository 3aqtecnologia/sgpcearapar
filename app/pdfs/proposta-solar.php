<?php
// require __DIR__ . '/../vendor/autoload.php';
require dirname(__DIR__, 1) . './../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setChroot(__DIR__);
$options->set('defaultFont', 'Helvetica', 'Roboto');
$options->setIsRemoteEnabled(true);

$dompdf = new Dompdf($options);

//$dompdf->loadHtmlFile(__DIR__ . '/base.html');

ob_start();
require __DIR__ . "/proposta-base.php";
//require __DIR__ . "/proposta.php?{$_GET['id']}";

$dompdf->loadHtml(ob_get_clean());

$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$nane = date('Y-m-d_H-i-s');
$dompdf->stream("proposta.$nane.pdf", array("Attachment" => false));
