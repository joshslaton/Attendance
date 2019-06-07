<?php
// INSTRUCTIONS: https://codeshack.io/super-fast-php-mysql-database-class/

$allowed = ["192.168.8.222", "192.168.8.221", "192.168.8.224", "127.0.0.1", "192.168.11.176"];

if(in_array($_SERVER["REMOTE_ADDR"], $allowed)){
  // Core\SMS::init($_SERVER["REMOTE_ADDR"], $_GET["idnumber"]);
}else{
  echo "[-] Disallowed";
}
?>
