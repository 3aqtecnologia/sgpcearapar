<?php
// require __DIR__ . '/../vendor/autoload.php';
//require dirname(__DIR__, 1) . './../vendor/autoload.php';
require dirname(__DIR__, 1) . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setChroot(__DIR__);
$options->set('defaultFont', 'Helvetica', 'Roboto');
$options->setIsRemoteEnabled(true);


$dompdf = new Dompdf($options);


//$dompdf->loadHtmlFile(__DIR__ . '/base.html');

ob_start();
require __DIR__ . "/certidaoDocBase.php";
//require __DIR__ . "/proposta.php?{$_GET['id']}";
$dompdf->set_option('dpi', '200');
$dompdf->loadHtml(ob_get_clean());

$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$date = date('Y-m-d_H-i-s');
$code = $_GET['code'];
$dataPub = base64_decode($code);
$dompdf->stream("Cert-cp_DocInst.$code em $dataPub.pdf", array("Attachment" => false));
