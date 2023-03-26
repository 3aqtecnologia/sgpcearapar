<?php

//INCLUI A CONEXÃO
//include_once "../conexao_odbc/conexao_odbc.php";
//define(SERVER, "SISTEMA"); //$Server = "SISTEMA";
//define(LOGIN, 'sa');     //$Login = "sa";
//define(SENHA, '123456'); //$Senha = "123456";
//$con = odbc_connect(SERVER, LOGIN, SENHA) or die(odbc_error()); //$con = odbc_connect($Server, $Login, $Senha) or die(odbc_error());

/* * ***************************
  GERA RESUMOS
 * *************************** */

function lmWord($string, $words = '100')
{
  $string = strip_tags($string);
  $count = strlen($string);

  if ($count <= $words) {
    return $string;
  } else {
    $strpos = strrpos(substr($string, 0, $words), ' ');
    return substr($string, 0, $strpos) . '...';
  }
}

/* * ***************************
  VALIDA O CPF
 * *************************** */

function valCpf($cpf)
{
  $cpf = preg_replace('/[^0-9]/', '', $cpf);
  $digitoA = 0;
  $digitoB = 0;
  for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
    @$digitoA += $cpf[$i] * $x;
  }
  for ($i = 0, $x = 11; $i <= 9; $i++, $x--) {
    if (str_repeat($i, 11) == $cpf) {
      return false;
    }
    @$digitoB += $cpf[$i] * $x;
  }
  $somaA = (($digitoA % 11) < 2) ? 0 : 11 - ($digitoA % 11);
  $somaB = (($digitoB % 11) < 2) ? 0 : 11 - ($digitoB % 11);
  if ($somaA != @$cpf[9] || $somaB != $cpf[10]) {
    return false;
  } else {
    return true;
  }
}

/* * ***************************
  VALIDA O EMAIL
 * *************************** */

function valMail($email)
{
  if (preg_match('/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/', $email)) {
    return true;
  } else {
    return false;
  }
}

/* * ***************************
  ENVIA O EMAIL
 * *************************** */

function sendMail($assunto, $mensagem, $remetente, $nomeRemetente, $destino, $nomeDestino, $reply = null, $replyNome = null)
{

  require_once 'mail/class.phpmailer.php'; //Include pasta/classe do PHPMailer

  $mail = new PHPMailer(); //INICIA A CLASSE
  $mail->IsSMTP(); //Habilita envio SMPT
  $mail->SMTPAuth = true; //Ativa email autenticado
  $mail->IsHTML(true);

  $mail->Host = MAILHOST; //Servidor de envio
  $mail->Port = MAILPORT; //Porta de envio
  $mail->Username = MAILUSER; //email para smtp autenticado
  $mail->Password = MAILPASS; //seleciona a porta de envio

  $mail->From = utf8_decode($remetente); //remtente
  $mail->FromName = utf8_decode($nomeRemetente); //remtetene nome

  if ($reply != null) {
    $mail->AddReplyTo(utf8_decode($reply), utf8_decode($replyNome));
  }

  $mail->Subject = utf8_decode($assunto); //assunto
  $mail->Body = utf8_decode($mensagem); //mensagem
  $mail->AddAddress(utf8_decode($destino), utf8_decode($nomeDestino)); //email e nome do destino

  if ($mail->Send()) {
    return true;
  } else {
    return false;
  }
}

/* * ***************************
  FORMATA DATA EM TIMESTAMP
 * *************************** */

function formatDate($data)
{
  $timestamp = explode(" ", $data);
  $getData = $timestamp[0];
  $getTime = $timestamp[1];

  $setData = explode('/', $getData);
  $dia = $setData[0];
  $mes = $setData[1];
  $ano = $setData[2];

  if (!$getTime) :
    $getTime = date('H:i:s');
  endif;

  $resultado = $ano . '-' . $mes . '-' . $dia . ' ' . $getTime;

  return $resultado;
}

/* * ***************************
  CALCULAR IDADE
 * *************************** */

function calcIdade($data)
{
  // Separa em dia, mês e ano
  list($dia, $mes, $ano) = explode('/', $data);
  // Descobre que dia é hoje e retorna a unix timestamp
  $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
  // Descobre a unix timestamp da data de nascimento do fulano
  $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);
  // Depois apenas fazemos o cálculo já citado :)
  $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

  return $idade;
}

/* * ***************************
  MANAGE ESTATÍSCAS
 * *************************** */

function viewManager($times = 2)
{
  $selMes = date('m');
  $selAno = date('Y');
  if (empty($_SESSION['startView']['sessao'])) {
    $_SESSION['startView']['sessao'] = session_id();
    $_SESSION['startView']['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['startView']['url'] = $_SERVER['PHP_SELF'];
    $_SESSION['startView']['time_end'] = time() + $times;
    create('up_views_online', $_SESSION['startView']);
    $readViews = read('up_views', "WHERE mes = '$selMes' AND ano = '$selAno'");
    if (!$readViews) {
      $createViews = array('mes' => $selMes, 'ano' => $selAno);
      create('up_views', $createViews);
    } else {
      foreach ($readViews as $views);
      if (empty($_COOKIE['startView'])) {
        $updateViews = array(
          'visitas' => $views['visitas'] + 1,
          'visitantes' => $views['visitantes'] + 1,
        );
        update('up_views', $updateViews, "mes = '$selMes' AND ano = '$selAno'");
        setcookie('startView', time(), time() + 60 * 60 * 24, '/');
      } else {
        $updateVisitas = array('visitas' => $views['visitas'] + 1);
        update('up_views', $updateVisitas, "mes = '$selMes' AND ano = '$selAno'");
      }
    }
  } else {
    $readPageViews = read('up_views', "WHERE mes = '$selMes' AND ano = '$selAno'");
    if ($readPageViews) {
      foreach ($readPageViews as $rpgv);
      $updatePageViews = array('pageviews' => $rpgv['pageviews'] + 1);
      update('up_views', $updatePageViews, "mes = '$selMes' AND ano = '$selAno'");
    }
    $id_sessao = $_SESSION['startView']['sessao'];
    if ($_SESSION['startView']['time_end'] <= time()) {
      delete('up_views_online', "sessao = '$id_sessao' OR time_end <= time(NOW())");
      unset($_SESSION['startView']);
    } else {
      $_SESSION['startView']['time_end'] = time() + $times;
      $timeEnd = array('time_end' => $_SESSION['startView']['time_end']);
      update('up_views_online', $timeEnd, "sessao = '$id_sessao'");
    }
  }
}

/* * ***************************
  Paginação de resultados
 * *************************** */

function paginacao($tabela, $maxResults = 4)
{
  if (!isset($_GET['pag'])) {
    $limit = 0;
  } else {
    $limit = $_GET['pag'] * $maxR;
  }

  $ler = ler('tb_estagiarios', '', "LIMIT {$limit}, {$maxR}");
  echo $todos = mysql_num_rows(ler('tb_estagiarios')) . '<br>';
  while ($result = mysql_fetch_array($ler)) {
    echo $result[0] . ' -';
    echo $result[1] . '<br>';
  }
  $np = ceil($todos / 5);
  for ($i; $i <= $np; $i++) {
    $pg = $i - 1;
    echo "<a href={$_SERVER["PHP_SELF"]}?pag={$pg}> {$i}  </a>";
  }

  return $rLimit;
}

//function paginar($tabela, $pag, $max) {
//    $pag = (!$pag ? '1': $pag);
//    $ini = ($pag*$max)-$max;
//
//    $con = odbc_connect(SERVER, LOGIN, SENHA) or die(odbc_error());
//
//    $qrLer = "SELECT * FROM {$tabela} LIMIT $ini,$max";
//    $exLer = odbc_exec($con, $qrLer) or die('Erro ao Ler a ' . $tabela . ' ' . odbc_error());
//}

function paginar($tabela, $where, $maximos, $link, $pag, $maxlinks = 8)
{
  $readPaginator = ler("$tabela", '', "$where");
  $total = count($readPaginator);
  if ($total > $maximos) {
    $paginas = ceil($total / $maximos);
    if ($width) {
      echo '<div class="paginator" style="width:' . $width . '">';
    } else {
      echo '<div class="paginator">';
    }
    echo '<a href="' . $link . '1">Primeira Página</a>';
    for ($i = $pag - $maxlinks; $i <= $pag - 1; $i++) {
      if ($i >= 1) {
        echo '<a href="' . $link . $i . '">' . $i . '</a>&nbsp;&nbsp;&nbsp;';
      }
    }
    echo '<span class="atv">' . $pag . '</span>&nbsp;&nbsp;&nbsp;';
    for ($i = $pag + 1; $i <= $pag + $maxlinks; $i++) {
      if ($i <= $paginas) {
        echo '<a href="' . $link . $i . '">' . $i . '</a>&nbsp;&nbsp;&nbsp;';
      }
    }
    echo '<a href="' . $link . $paginas . '">Última Página</a>';
    echo '</div><!-- /paginator -->';
  }
}




/* * ***************************
  GERA NUMERO DE MATRICULA
 * *************************** */

function nMatricula($tabela, $coluna, $casas)
{
  $lerAlunos = ler($tabela);
  $contAlunos = mysql_num_rows($lerAlunos);
  $nMatricula = date("Ym");
  if ($contAlunos == 0) {
    $sequencia = $contAlunos + 1;
    $nMatricula .= str_pad($sequencia, $casas, "0", STR_PAD_LEFT);
  } elseif ($contAlunos != 0) {
    $maiorAluno = ler($tabela, "max({$coluna})");
    $maiorMatricula = mysql_fetch_array($maiorAluno);
    $nMatricula = $maiorMatricula[0] + 1;
  }
  return $nMatricula;
}

/* * ***************************
  GERA NUMERO DE INSCRIÇÃO
 * *************************** */

//function matricula($tabela, $coluna, $casas) {
function matricula($tabela, $casas)
{
  $lerAlunos = ler($tabela);
  $contAlunos = $lerAlunos->rowCount();
  //    if ($contAlunos == 0) {
  $sequencia = $contAlunos + 1;
  $nMatricula = str_pad($sequencia, $casas, "0", STR_PAD_LEFT);
  //    } elseif ($contAlunos != 0) {
  //        $maiorAluno = ler($tabela, "max({$coluna})");
  //        $maiorMatricula = $maiorAluno->fetchAll(PDO::FETCH_ASSOC);
  //        $sequencia = $maiorMatricula + 1;
  //        $nMatricula = str_pad($sequencia, $casas, "0", STR_PAD_LEFT);
  //    }
  return date("Y", time()) . $nMatricula;
}

/* * ***************************
  GERA NUMERO DE CONTRATO
 * *************************** */

//function matricula($tabela, $coluna, $casas) {
function nContrato($tabela, $casas)
{
  $contratos = ler($tabela)->rowCount();
  $sequencia = $contratos + 1;
  $nContato = str_pad($sequencia, $casas, "0", STR_PAD_LEFT);
  $ano = date("Y", time());
  $mes = date("m", time());
  return $ano . $mes . $nContato;
}
/* * ***************************
  GERA NUMERO DE PROCESSO
 * *************************** */

//function matricula($tabela, $coluna, $casas) {
function nProcesso($tabela, $idCliente)
{
  $contratos = ler($tabela)->rowCount();
  $sequencia = $contratos + 1;
  $nProcess = str_pad($sequencia, 5, "0", STR_PAD_LEFT);
  $ano = date("Y", time());
  return $idCliente . $ano . $nProcess;
}

/* * ***************************
  UPLOAD DE AQUIVOS
 * *************************** */
function upLoadFile($file, $tamanho, array $extensoes, $pasta, $largura = null)
{
  //Largura para redimencionar imagens
  $largura = ($largura != null ? $largura : 768);

  // Pasta onde o arquivo vai ser salvo
  $_UP['pasta'] = $pasta . '/';

  // Tamanho máximo do arquivo (em Bytes)
  $_UP['tamanho'] = 1024 * 1024 * $tamanho;

  // Array com as extensões permitidas
  $_UP['extensoes'] = $extensoes;


  // Array com os tipos de erros de upload do PHP
  $_UP['erros'][0] = 'Não houve erro';
  $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
  $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
  $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
  $_UP['erros'][4] = 'Não foi feito o upload do arquivo';

  // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
  if ($file['error'] != 0) {
    return "Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$file['error']];
    exit; // Para a execução do script
  }

  // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar


  // Faz a verificação da extensão do arquivo
  $extensao = strtolower(explode('.', $file['name'])[1]);
  if (array_search($extensao, $_UP['extensoes']) === false) {

    foreach ($extensoes as $filds => $values) {
      $extensoesAceitas[] = $values;
    }
    $extensoesAceitas = implode(", ", $extensoesAceitas);
    return
      "<script type='text/javascript'> alert('Por favor, envie arquivo com em uma dos segiuontes formatos: {$extensoesAceitas}');
    </script>";
  }

  if ($extensao == 'pdf') {
    if ($_UP['tamanho'] < $file['size']) {
      return
        "<script type='text/javascript'> alert('O arquivo enviado é muito grande, envie arquivos de até {$tamanho}Mb.');
        window.location = '../../../inicio.php?page=listardespesas';
        </script>";
    }
  }

  // Faz a verificação do tamanho do arquivo
  //if ($_UP['tamanho'] < $file['size']) {
  // return "muito grande{$file['size']} ";
  // "<script type='text/javascript'> alert('O arquivo enviado é muito grande, envie //arquivos de até {$tamanho}Mb.');
  //    window.location = '../../../inicio.php?page=listardespesas';
  //   </script>";
  //  }
  // Cria um nome baseado no UNIX TIMESTAMP atual
  // $nome_final = time() . '.' . substr($file['name'], -3);
  $nome_final = time() . '.' . explode('.', $file['name'])[1];

  //$tipo = strtolower(reset(explode('/', $file['type'])));

  if (strtolower(explode('/', $file['type'])[1]) == 'image') {
    // Cria identificador para nova imagem
    if ($file['type'] == "image/jpeg") {
      $img = imagecreatefromjpeg($file['tmp_name']);
    } else if ($file['type'] == "image/gif") {
      $img = imagecreatefromgif($file['tmp_name']);
    } else if ($file['type'] == "image/png") {
      $img = imagecreatefrompng($file['tmp_name']);
    }
    $x = imagesx($img);
    $y = imagesy($img);
    $autura = ($largura * $y) / $x;
    //imagem servirá de base para a imagem a ser reduzida
    $nova = imagecreatetruecolor($largura, $autura);
    // Faz a interpolação da imagem base com a imagem original
    imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $autura, $x, $y);

    if ($file['type'] == "image/jpeg") {
      $newImage =  imagejpeg($nova, $_UP['pasta'] . $nome_final, 90);
    } else if ($file['type'] == "image/gif") {
      $newImage = imagejpeg($nova, $_UP['pasta'] . $nome_final, 90);
    } else if ($file['type'] == "image/png") {
      $newImage = imagejpeg($nova, $_UP['pasta'] . $nome_final, 90);
    }
    @imagedestroy($img);
    @imagedestroy($nova);
    return  $nome_final;

    // if (move_uploaded_file($newImage, $_UP['pasta'] . $nome_final)) {
    // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
    //    echo "Upload efetuado com sucesso!";
    //    return  $nome_final;
    //  } else {
    //     // Não foi possível fazer o upload, provavelmente a pasta está incorreta
    //    echo "Não foi possível enviar o arquivo, tente novamente";
    //  }
  }

  // Depois verifica se é possível mover o arquivo para a pasta escolhida
  elseif (move_uploaded_file($file['tmp_name'], $_UP['pasta'] . $nome_final)) {
    // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
    // echo "Upload efetuado com sucesso!";
    return  $nome_final;
  } else {
    // Não foi possível fazer o upload, provavelmente a pasta está incorreta
    echo "Não foi possível enviar o arquivo, tente novamente";
  }
}

/* * ***************************
  REDIMENTIONAR IMAGENS
 * *************************** */

function redimencionar($imagem, $largura, $pasta, $name)
{
  if ($imagem['type'] == "image/jpeg") {
    $img = imagecreatefromjpeg($imagem['tmp_name']);
  } else if ($imagem['type'] == "image/gif") {
    $img = imagecreatefromgif($imagem['tmp_name']);
  } else if ($imagem['type'] == "image/png") {
    $img = imagecreatefrompng($imagem['tmp_name']);
  }
  @$x = imagesx($img);
  @$y = imagesy($img);
  @$autura = ($largura * $y) / $x;
  @$nova = imagecreatetruecolor($largura, $autura);
  @imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $autura, $x, $y);
  if ($imagem['type'] == "image/jpeg") {
    @$local = "$pasta/$name" . ".jpg";
    imagejpeg($nova, $local);
  } else if ($imagem['type'] == "image/gif") {
    @$local = "$pasta/$name" . ".gif";
    imagejpeg($nova, $local);
  } else if ($imagem['type'] == "image/png") {
    @$local = "$pasta/$name" . ".png";
    imagejpeg($nova, $local);
  }
  @imagedestroy($img);
  @imagedestroy($nova);
  return @$local;
}
/* * ***************************
  RETIRAR MASCARAS
 * *************************** */
function tiraMascara($dado)
{
  $semMascara = preg_replace("/\D+/", "", $dado);
  return $semMascara;
}

function MascaraCNJ($dado)
{
  $mascaraCNJ = substr($dado, 0, 7) . "-" . substr($dado, 7, 2) . "." . substr($dado, 9, 4) . "." . substr($dado, 13, 1) . "." . substr($dado, 14, 2) . "." . substr($dado, -4);
  return $mascaraCNJ;
}

function MascaraCPF($dado)
{
  $mascaraCPF = substr($dado, 0, 3) . "." . substr($dado, 3, 3) . "." . substr($dado, 6, 3) . "-" . substr($dado, -2);
  return $mascaraCPF;
}
function MascaraCEP($dado)
{
  $mascaraCEP = substr($dado, 0, 5) . "-" . substr($dado, -3);
  return $mascaraCEP;
}

function MascaraCNPJ($dado)
{
  $mascaraCPF = substr($dado, 0, 2) . "." . substr($dado, 2, 3) . "." . substr($dado, 5, 3) . "/" . substr($dado, 8, 4) . "-" . substr($dado, -2);
  return $mascaraCPF;
}

function MascaraPIS($dado)
{
  $mascaraPIS = substr($dado, 0, 3) . "." . substr($dado, 3, 4) . "." . substr($dado, 7, 3) . "-" . substr($dado, -1);
  return $mascaraPIS;
}

function formatMoedaBr($dado)
{
  return number_format($dado, 2, ',', '.');
}

function SomarData($data, $dias, $meses, $ano)
{
  //passe a data no formato dd/mm/yyyy
  $data = explode("/", $data);
  $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $ano));
  return $newData;
}

function valorPorExtenso($valor = 0)
{
  $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
  $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
  $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
  $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
  $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
  $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
  $z = 0;
  $valor = number_format($valor, 2, ".", ".");
  $inteiro = explode(".", $valor);
  for ($i = 0; $i < count($inteiro); $i++) {
    for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) {
      $inteiro[$i] = "0" . $inteiro[$i];
    }
  }

  // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
  $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
  for ($i = 0; $i < count($inteiro); $i++) {
    $valor = $inteiro[$i];
    $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
    $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
    $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
    $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
    $t = count($inteiro) - 1 - $i;
    $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
    if ($valor == "000") {
      $z++;
    } elseif ($z > 0) {
      $z--;
    }

    if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) {
      $r .= (($z > 1) ? " de " : "") . $plural[$t];
    }

    if ($r) {
      $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
    }
  }
  return ($rt ? $rt : "zero");
}

function valorPorExtensoDois($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
{
  // $valor = self::removerFormatacaoNumero( $valor );
  $singular = null;
  $plural = null;
  if ($bolExibirMoeda) {
    $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
    $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
  } else {
    $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
    $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
  }
  $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
  $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
  $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
  $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
  if ($bolPalavraFeminina) {
    if ($valor == 1) {
      $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
    } else {
      $u = array("", "um", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
    }
    $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
  }
  $z = 0;
  $valor = number_format($valor, 2, ".", ".");
  $inteiro = explode(".", $valor);
  for ($i = 0; $i < count($inteiro); $i++) {
    for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++) {
      $inteiro[$i] = "0" . $inteiro[$i];
    }
  }

  // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
  $rt = null;
  $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
  for ($i = 0; $i < count($inteiro); $i++) {
    $valor = $inteiro[$i];
    $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
    $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
    $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
    $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
    $t = count($inteiro) - 1 - $i;
    $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
    if ($valor == "000") {
      $z++;
    } elseif ($z > 0) {
      $z--;
    }

    if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) {
      $r .= (($z > 1) ? " de " : "") . $plural[$t];
    }

    if ($r) {
      $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
    }
  }
  $rt = mb_substr($rt, 1);
  return ($rt ? trim($rt) : "zero");
}
