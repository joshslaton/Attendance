<?php
if(!is_null($_GET["dir"]) && !is_null($_GET["idnumber"])) {
  $d = new DateTime();
  error_log($_GET["idnumber"]." -> ".$d->format("Y-m-d h:i:s"));
  $schoolYear = 2019;
  $command = Core\db::query(
    array(
      "INSERT INTO proj_attendance (idnumber, gate, time, syear) VALUES (?, ?, ?, ?)",
      array($_GET["idnumber"], $_GET["dir"], $d->format("Y-m-d h:i:s"), $schoolYear)));
  if($command) {
    error_log("INSERT TO DATABASE SUCCESS!");
  } else {
    error_log("INSERT TO DATABASE FAILED!");
  }
}
