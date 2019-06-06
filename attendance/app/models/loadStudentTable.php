<?php

/*
grade
section per student
array
*/

if(isset($_POST["ccode"]) && $_POST["ccode"] != "") {
  $grade = $_POST["ccode"];
  $students = Core\db::query(array("SELECT * FROM proj_student WHERE ccode = ?", array($grade)));

  echo json_encode($students);
}
?>
