<?php
$bootstrapFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
if(file_exists($bootstrapFile) && is_file($bootstrapFile)){
  require_once($bootstrapFile);
}else{
  echo "File missing";
  exit;
}
?>
