<?php
// $_POST["ccode"] = "KINDER";
// print_r($_POST["ccode"]);
if(isset($_POST["ccode"]) && $_POST["ccode"] != "") {
  $grade = $_POST["ccode"];
  $students = Core\db::query(array("SELECT * FROM proj_student WHERE ccode = ?", array($grade)));

  $data = array();

  foreach($students as $student) {
    $data[] = $student;
  }
  echo json_encode($data);
}
