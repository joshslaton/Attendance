<?php
$range = "";
// SETTING YEAR of SCHOOLYEAR IF NOT SET
if(isset($_SESSION["schoolYear"])) {
  if($results = Core\db::query(array("SELECT name FROM proj_sy WHERE syear = ?", array($_SESSION["schoolYear"])))) {
    $range = explode("-", $results[0]["name"]);
  }
}else {
  $_SESSION["schoolYear"] = date("Y");
}


// ACTIONS FOR AJAX URL REQUESTS
if($_POST["action"] != NULL) {
  if($_POST["action"] == "view"){
    if($_POST["viewType"] == "DTR"){

        $tbl = "";
        $tbl .= "<table id='attendanceSheet'>";
        foreach($range as $year){

          // Year
          $tbl .= "<tr><th id='attendanceYearLabel' colspan=13>".$year."</th></tr>";
          // Month
          $tbl .= "<tr>";
          for($month = 0; $month <= 12; $month ++) {
            $tbl .= $month == 0 ? "<th></th>" : "<th>".date("M", mktime(0, 0, 0, $month, 1, $year))."</th>";
          }
          $tbl .= "</tr>";
          // IN and OUT Labels
          $tbl .= "<tr>";
          for($month = 0; $month <= 12; $month ++) {
            $tbl .= $month == 0 ? "<th></th>" : "<td><div class='row'><div class='column column-header'>IN</div><div class='column column-header'>OUT</div></div></td>";
          }
          $tbl .= "</tr>";

          for($date = 1; $date <= 31; $date ++) {
            $tbl .= "<tr>";
            $tbl .= "<td id='dayLabel'>$date</td>";
            for($month = 1; $month <= 12; $month ++) {
              $numOfDays = date("t", mktime(0, 0, 0, $month, 1, $year));
              if($date <= $numOfDays){
                $d = strlen($date) == 1 ? str_pad($date, 2, "0", STR_PAD_LEFT) : $date;
                $m = strlen($month) == 1 ? str_pad($month, 2, "0", STR_PAD_LEFT) : $month;
                $tbl .= "<td rel='$year-$m-$d'><div class='row'><div id='in' class='column column-content'></div><div id='out' class='column column-content'></div></div></td>";
              }
              else
                $tbl .= "<td style='background-color: #ccc;'></td>";

            }
            $tbl .= "</tr>";
          }


        }
        $tbl .= "</table>";
        $tbl .= "</br></br></br>";
        echo $tbl;
    }
    if($_POST["viewType"] == "Classcard"){

        $labels = ["present", "absent", "tardy"];
        $years = Core\db::query(array("SELECT name FROM proj_sy WHERE syear = ?", array($_SESSION["schoolYear"])));
        $years = explode("-", $years[0]["name"]);

        $tbl = "";
        $tbl .= "<table id='attendanceSheet' class='table table-sm table-bordered' style='width: auto' data-script='test'>";
        foreach($years as $year) {
          $tbl .= "<thead>";
          $tbl .= "<tr>";
          for($i = 0; $i <= 13; $i++){
            if($i == 0)
              $tbl .= "<th>".$year."</th>";
            elseif($i == 13)
              $tbl .= "<th>Total</th>";
            else
              $tbl .= "<th>".date("F",mktime(0, 0, 0, $i, 1, $year))."</th>";
          }
          $tbl .= "</tr>";
          $tbl .= "</thead>";

          $tbl .= "<tbody>";
          foreach($labels as $label) {
            $tbl .= "<tr>";
            for($m = 0; $m <= 13; $m++){
              if($m == 0)
                $tbl .= "<td>".ucfirst($label)."</td>";
              elseif ($m == 13)
                $tbl .= "<td rel='$label total'></td>";
              else
                $tbl .= "<td rel='$year-$m $label' id='$label'></td>";
            }
            $tbl .= "</tr>";
          }
        }
        $tbl .= "</tbody>";
        $tbl .= "</table>";
        $tbl .= "<br><br>";

        echo $tbl;
    }
  }
  if($_POST["action"] == "update"){
    $idnumber = $_POST["idnumber"];
    $year = $_SESSION["schoolYear"];

    if($_POST["viewType"] == "DTR"){
      $results = Core\db::query(array("SELECT gate, time FROM proj_attendance WHERE idnumber = ? and syear = ?", array($idnumber, $year)));
      echo json_encode($results);
    }
    if($_POST["viewType"] == "Classcard" ){
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
}
