<?php
// $_POST["ccode"] = "KINDER";
if(isset($_POST["ylevel"]) && $_POST["ylevel"] != "") {
  $grade = $_POST["ylevel"];
  $students = Core\db::query(array("SELECT idnumber, CONCAT(UPPER(lname), ', ', mname, ', ', fname) AS sname FROM proj_student WHERE ylevel = ?", array($grade)));

  $data = array();

  foreach($students as $student) {
    $data[] = $student;
  }
  echo json_encode($students);
}
