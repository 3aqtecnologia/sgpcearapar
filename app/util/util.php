<?php

function sweetalert($title, $message, $type, $time = null, $position = null)
{
  $position = $position ? $position : 'center';
  $time = $time ? $time : '3000';
  echo "<script type='text/javascript'>
  Swal.fire({
    icon: '$type',
    title: '$title',
    text: '$message',
    showConfirmButton: false,
    timer: $time,
    position: '$position',
  });
  </script>";
}

function GetDirectorySize($path)
{
  $bytestotal = 0;
  $path = realpath($path);
  if ($path !== false) {
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
      $bytestotal += $object->getSize();
    }
  }
  $bytes = $bytestotal;
  $si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
  $base = 1024;
  $class = min((int)log($bytes, $base), count($si_prefix) - 1);

  return sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class];
}
function GetDirectoryFreeSize($path, $storage)
{
  $base = 1024;
  $storage = $storage * 1e+9;
  $bytestotal = 0;
  $path = realpath($path);
  if ($path !== false) {
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
      $bytestotal += $object->getSize();
    }
  }
  $bytes = $storage - $bytestotal;
  $si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
  // $base = 1024;
  $class = min((int)log($bytes, $base), count($si_prefix) - 1);

  return sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class];
}
function GetDirectoryPercentageUsed($path, $storage, $unit = null)
{
  $base = 1024;
  $unit = null ? $unit = 'GB' : $unit = $unit;
  switch ($unit) {
    case 'GB':
      $storage = $storage * 1e+9;
      break;
    case 'MB':
      $storage = $storage * 1e+6;
      break;
    case 'KB':
      $storage = $storage * 1e+3;
      break;
    default:
      $storage = $storage * 1e+9;
      break;
  }
  //$storage = pow($storage * $base, 3);
  $bytestotal = 0;
  $path = realpath($path);
  if ($path !== false) {
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
      $bytestotal += $object->getSize();
    }
  }

  $percentage = ($bytestotal  * 100) / $storage;
  return number_format($percentage, 2, '.', '');
}

function uuidv4($data = null)
{
  // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
  $data = $data ?? random_bytes(16);
  assert(strlen($data) == 16);

  // Set version to 0100
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
  // Set bits 6-7 to 10
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

  // Output the 36 character UUID.
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
function dataExtenso($prefix = '', $diaS = false,  $time = 'now')
{
  $hoje = strtotime($time);
  $i = getdate($hoje); // Consegue informações data/hora
  $data = $i['mday']; //Representação numérica do dia do mês (1 a 31)
  $dia = $i['wday']; // representação numérica do dia da semana com 0 (para Domingo) a 6 (para Sabado)
  $mes = $i['mon'] - 1; // Representação numérica de um mês (1 a 12)
  $ano = $i['year']; // Ano com 4 digitos, lógico, né?
  $data = str_pad($data, 2, "0", STR_PAD_LEFT); // só para colocar um zerinho à esquerda caso seja de 1 à 9, sacou?
  $nomedia = array("Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado");
  $nomemes = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
  if ($diaS) {
    return "$prefix{$nomedia[$dia]}, $data de {$nomemes[$mes]} de $ano";
  } else {
    return "$prefix $data de {$nomemes[$mes]} de $ano";
  }
}
function dataExtensoDB($prefix = '', $data = null)
{
  $data = $data ? $data : date('Y-m-d', time());
  $i = explode('-', $data); // Consegue informações data/hora
  $data = $i['2']; //Representação numérica do dia do mês (1 a 31)
  // $dia = $i['']; // representação numérica do dia da semana com 0 (para Domingo) a 6 (para Sabado)
  $mes = $i['1'] - 1; // Representação numérica de um mês (1 a 12)
  $ano = $i['0']; // Ano com 4 digitos, lógico, né?
  $data = str_pad($data, 2, "0", STR_PAD_LEFT); // só para colocar um zerinho à esquerda caso seja de 1 à 9, sacou?
  $nomedia = array("Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado");
  $nomemes = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

  return "$prefix $data de {$nomemes[$mes]} de $ano";
}

function valorExtenso()
{

  $data = date('D');
  $mes = date('M');
  $dia = date('d');
  $ano = date('Y');

  $semana = array(
    'Sun' => 'Domingo',
    'Mon' => 'Segunda-Feira',
    'Tue' => 'Terca-Feira',
    'Wed' => 'Quarta-Feira',
    'Thu' => 'Quinta-Feira',
    'Fri' => 'Sexta-Feira',
    'Sat' => 'Sábado'
  );

  $mes_extenso = array(
    'Jan' => 'Janeiro',
    'Feb' => 'Fevereiro',
    'Mar' => 'Março',
    'Apr' => 'Abril',
    'May' => 'Maio',
    'Jun' => 'Junho',
    'Jul' => 'Julho',
    'Aug' => 'Agosto',
    'Nov' => 'Novembro',
    'Sep' => 'Setembro',
    'Oct' => 'Outubro',
    'Dec' => 'Dezembro'
  );

  $data = $semana["$data"] . ", {$dia} de " . $mes_extenso["$mes"] . " de {$ano}";

  return $data;
}
