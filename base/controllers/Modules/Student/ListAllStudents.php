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

if(!is_null($_POST["searchByStudent"])) {
  $s = $_POST["searchByStudent"];

  // TODO: Sanitize
  // ID Number search
  if(is_numeric($s)) {

    // Fuzzy string search on MySQL using LIKE
    if(strlen($s) < 7) {
      $input = $s . "%";
      $students = Core\db::query(array("SELECT idnumber, CONCAT(UPPER(lname), ', ', mname, ', ', fname) AS sname FROM proj_student WHERE idnumber LIKE ?", array($input)));
      echo json_encode($students);
    }

    if(strlen($s) == 7) {
      $input = $s;
      $students = Core\db::query(array("SELECT idnumber, CONCAT(UPPER(lname), ', ', mname, ', ', fname) AS sname FROM proj_student WHERE idnumber = ?", array($input)));
      echo json_encode($students);
    }
  }

  // Name search
  else {
    $input = "%".$s."%";
    $students = Core\db::query(array("SELECT idnumber, CONCAT(UPPER(lname), ', ', mname, ', ', fname) AS sname FROM proj_student WHERE CONCAT(UPPER(lname), ', ', mname, ', ', fname) LIKE ?", array($input)));
    if($students) {
      echo json_encode($students);
    }
  }
}
