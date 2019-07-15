<?php
if($_POST["idnumber"] != NULL) {
  $p = "RtplJU6P6iPZjpkp0cn3MCvL2uDwMapA";
  $id = $_POST["idnumber"];
  $sec = sha1($id.$p);

  echo $sec;
}
