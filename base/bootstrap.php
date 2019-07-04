<?php

$currentDir = dirname(__FILE__);
$dirs = array();

$dirs[] = $currentDir . DIRECTORY_SEPARATOR . "controllers";
$dirs[] = $currentDir . DIRECTORY_SEPARATOR . "models";

foreach($dirs as $dir){
  if(!is_dir($dir) && file_exists($dir)){
    continue;
  }
  $files = scandir($dir);
  foreach($files as $file){
    if(
      file_exists($dir . DIRECTORY_SEPARATOR . $file) &&
      is_file($dir . DIRECTORY_SEPARATOR . $file) &&
      substr($file, 0, 1) !== "."
    ) {
        require_once($dir . DIRECTORY_SEPARATOR . $file);
      }
  }

}

$host = $_SERVER["HTTP_HOST"];
$configFilepath = $currentDir . DIRECTORY_SEPARATOR . "configs/" . $host . ".config.php";
if(file_exists($configFilepath) && is_file($configFilepath)){
  require($configFilepath);
}else{
  echo "Config file is missing! Please contact IT Administrator.";
  error_log("Config file is missing! Please contact IT Administrator.");
  exit();
}
