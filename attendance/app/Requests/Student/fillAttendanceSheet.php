<?php
if(isset($_POST["idnumber"]) && strlen($_POST["idnumber"]) == 7) {
  $idnumber = $_POST["idnumber"];
  $year = $_SESSION["schoolYear"];

  if($_POST["calendarType"] == "DTR"){
    $results = Core\db::query(array("SELECT gate, time FROM proj_attendance WHERE idnumber = ? and syear = ?", array($idnumber, $year)));
    echo json_encode($results);
  }



  if($_POST["calendarType"] == "Classcard"){
    $temp = array(
      "present" => array(),
      // "absent" => array(),
      // "tardy" => array(),
    );
    // query to adapt to classcard setting
    for($y = $year; $y <= $year+1; $y++){
      $temp["present"][$y] = array();
      for($i = 1; $i <= 12; $i++){
        $m = date("n", mktime(0, 0, 0, $i, 1, $year));

        // Params: MONTH, YEAR, ID NUMBER, SCHOOL YEAR
        $q = "SELECT COUNT(DISTINCT day(time)) as '".$m."' FROM proj_attendance WHERE day(time) between 1 and 31 && month(time) = ? && year(time) = ? && idnumber = ? && syear = ?";
        $results = Core\db::query(array($q, array($i, $y, $idnumber, $year)));
        $temp["present"][$y] += $results[0];
      }
    }
    echo json_encode($temp);
  }

}
