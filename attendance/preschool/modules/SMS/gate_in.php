<?php
// INSTRUCTIONS: https://codeshack.io/super-fast-php-mysql-database-class/
include './sms_in.php';
$allowed = ["192.168.8.222", "192.168.8.221", "192.168.8.224"];

if(in_array($_SERVER["REMOTE_ADDR"], $allowed)){
  $sms = new SMS();
  $sms->init($_SERVER["REMOTE_ADDR"], $_GET["idnumber"]);
}else{
  echo "[-] Disallowed";
}
?>
