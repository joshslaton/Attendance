<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/includes/DB.inc.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/classes/student.php";
$db = new db();
$s = new student();

if(isset($_GET['gender']) && isset($_GET['section'])){
  $gender = explode(",", $_GET['gender']);
  $section = $_GET['section'];

  $stmt = "";
  if(count($gender) == 2){
    $stmt = "('m' && 'f')";
  }elseif(count($gender) == 1){
    $stmt = "'".implode($gender)."'";
  }

  $studentArray = Array();
  $q = "SELECT CONCAT(idnumber, ' ', name) as student FROM preschool WHERE section = '".$section."' AND gender = ".$stmt;
  $results = $db->query($q)->fetchAll();

  foreach($results as $result){
    array_push($studentArray, $result['student']);
  }
  echo $s->showRoster($studentArray);
}
?>
