<?php

/*
grade
section per student
array
*/
if(isset($_POST["grade"]) && $_POST["grade"] != "") {
  $grade = strtolower($_POST["grade"]);
  $students = Core\db::query(array("SELECT idnumber, name, grade, section FROM preschool WHERE grade = ?", array($grade)));
  
  echo json_encode($students);
}
?>
