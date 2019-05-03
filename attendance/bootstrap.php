<?php

// File to include MVC folders and its contents, except index.php
session_start();

$appDir = dirname(__file__) . DIRECTORY_SEPARATOR . 'app';
$dirs = array();
$dirs[] = $appDir . DIRECTORY_SEPARATOR . 'controllers';
$dirs[] = $appDir . DIRECTORY_SEPARATOR . 'models';

foreach($dirs as $dir){
  if(!file_exists($dir) || !is_dir($dir)){
    continue;
  }
  $files = scandir($dir);
  foreach($files as $file){
    if(
        file_exists($dir . DIRECTORY_SEPARATOR . $file) &&
        is_file($dir . DIRECTORY_SEPARATOR . $file) &&
        substr($file, 0, 1) !== '.' &&
        substr($file, 0, 5) !== 'index'
      ){
        require_once($dir . DIRECTORY_SEPARATOR . $file);
      }
  }
}

$host = explode(":", $_SERVER['HTTP_HOST']);
$configFilepath = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'configs', $host[0]. '.config.php'));

if(file_exists($configFilepath) && is_file($configFilepath)){
  require_once($configFilepath);
} else {
    echo 'An important file is missing. Please contact your I.T. Personnel to look into this matter.';
    error_log($configFilepath . ' is missing.', 0);
    exit;
}
?>
