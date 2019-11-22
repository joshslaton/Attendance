<?php
include("../Core/Controller.php");
include("../Controllers/apiController.php");

function checkID($id) {
  if(is_numeric($id)) {
    return (strlen($id) >= 3 && strlen($id) <= 7);
  }
}

if(isset($_GET["dir"]) && isset($_GET["idnumber"])) {
  if($_GET["dir"] != "") {
    if(checkID($_GET["idnumber"])) {
      $idnumber = $_GET["idnumber"];
      $gate = $_GET["dir"];
      $time = new DateTime();
      $schoolYear = 2019;
      $data = array(
        "idnumber" => $_GET["idnumber"],
        "gate" => $_GET["dir"],
        "time" => new DateTime(),
        "schoolYear" => 2019,
      );
      $api = new apiController();
      $api->evaluate($data);
    }
  }
}
