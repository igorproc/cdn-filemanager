<?php

/**
 * @param File image
 * @return String path
 */
function FileImageUpload($file, string $serverToken) {
  $secret_key = "cHJpdmF0ZV9jZHNhZG5fU1BUWUZSRUVfa2V5";
  if($serverToken != $secret_key) return ['code' => 500];

  if ($file['error'] !== 0) return ['code' => 500];

  $floaderType = getFloaderFromFileType($file['type']);
  if(!$floaderType) return ['code' => 500];

  $filename = $file['name'];
  $size = $file['size'];
  if ($size > 30000000) return ['code' => 500];

  $tmp_name = $file['tmp_name'];

  $paths = FileGeneratorUploadPath($floaderType, $filename, $size);

  if(!is_writable($paths['private_without_filename'])) {
    mkdir(
      $paths['private_without_filename'],
      0777,
      true
    );
  }

  move_uploaded_file($tmp_name, $paths['private']);

  return [
    'code' => 200,
    'path' => $paths['public']
  ];
}

function getFloaderFromFileType(string $fileType) {
  $allowMusicTypes = [ 'audio/mp3', 'audio/flac', 'audio/wav', 'audio/wma', 'audio/aac', 'audio/mpeg' ];
  $allowImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
  if (in_array($fileType, $allowImageTypes, true)) return 'image';
  if (in_array($fileType, $allowMusicTypes, true)) return 'song';
  return '';
}

function getStringWithZeros(int $filenameLength) {
  $currentString = "";
  for ($i=0; $i < $filenameLength; $i++) {
    $currentString = $currentString . "0";
  }
  return $currentString;
}

function getUploadFileRange(string $filename) {
  $splittedFilename = explode('.', $filename);

  if(strlen($splittedFilename[0]) <= 3) return "0-999";

  $rangeTemplate = getStringWithZeros(strlen($splittedFilename[0]) - 1);
  $rangeStrart = $filename[0] . $rangeTemplate;
  $rangeEnd = $filename[0] . substr($rangeTemplate, 0, -3) . "999";
  return $rangeStrart . "-" . $rangeEnd;
}

function FileGeneratorUploadPath (string $type, string $filename, int $size) {
  global $config;

  $filename_format = array_pop(explode('.', $filename));
  $dir = '/' . getUploadFileRange($filename);
  $prefix = $dir . '/';
  $postfix = '.'.$filename_format;

  switch ($type) {
    case 'image':
      $prefix .= 'uc/';
      break;
    case 'song':
      $prefix .= 'ur/';
      break;
  }

  $gen = $prefix.FileGeneratorFilename($filename, $size).$postfix;

  $paths = [
    'public' => $config['mainAppUrl'].$gen,
    'private' => $_SERVER['DOCUMENT_ROOT'].$gen,
    'private_without_filename' => $_SERVER['DOCUMENT_ROOT'].$prefix
  ];

  return $paths;
}

function FileGeneratorFilename (string $filename, int $size) {
  $a = md5($filename . '..−. .− .−−− .−..' . $size);
  $b = time();
  $c = $filename .= ' ..−. .− .−−− .−..';
  $d = $size .= ' ..−. .− .−−− .−..';
  $f = hash('sha256', '0'.$a.'0'.$b.'0'.$c.'0'.$d.'0');
  $name = substr($f, 0, 8);
  return $name;
}
?>