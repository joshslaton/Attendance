<?php
if(isset($_POST["idnumber"]) && strlen($_POST["idnumber"]) == 7) {
  $idnumber = $_POST["idnumber"];
  $results = Core\db::query(array("SELECT gate, time FROM proj_attendance WHERE idnumber = ? and syear = ?", array($idnumber, $_SESSION["schoolYear"])));

  echo json_encode($results);
}