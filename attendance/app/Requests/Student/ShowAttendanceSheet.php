<?php
$range = "";
// $range = array("2018", "2019");
if(isset($_SESSION["schoolYear"])) {
  if($results = Core\db::query(array("SELECT name FROM proj_sy WHERE syear = ?", array($_SESSION["schoolYear"])))) {
    $range = explode("-", $results[0]["name"]);
  }
}else {
  $_SESSION["schoolYear"] = date("Y");
}

// build calendar here according to calendarType/viewType
// $_POST["calendarType"] = "DTR";
if(isset($_POST["calendarType"])){

  if($_POST["calendarType"] == "DTR"){
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

  if($_POST["calendarType"] == "DTR2"){
    $tbl = "";
    $tbl .= "<table id='attendanceSheet'>";
    foreach($range as $year){

      // YEAR
      $tbl .= "<tr id='attendanceYearLabel'><th colspan=12 style=''><h3>$year</h3></th></tr>";

      // DATE 1 - 10
      $tbl .= "<tr><th>Month</th><th colspan=11>Date</th></tr>";
      $tbl .= "<tr style='text-align: center;'>";
      $tbl .= "<th></th>";
      for($i = 1; $i <= 11; $i++) {
        $tbl .= "<th>".$i."</th>";
      }
      $tbl .= "</tr>";

      // IN or OUT LABELS
      $tbl .= "<tr style='text-align: center;'>";
      $tbl .= "<th></th>";
      for($i = 1; $i <= 11; $i++) {
        $tbl .= "<th><div class='row'><div class='column column-header'>IN</div><div class='column column-header'>OUT</div></div></th>";
      }
      $tbl .= "</tr>";

      // MONTH LABEL
      for ($m = 1; $m <= 12; $m++) {
        $tbl .= "<tr style='text-align: center;'>";
        $tbl .= "<th>".date("M", mktime(0, 0, 0, $m, 1, $year))."</th>";
        for($d = 1; $d <= 11; $d++){
          $d = strlen($d) == 1 ? str_pad($d, 2,"0",STR_PAD_LEFT) : $d;
          $m = strlen($m) == 1 ? str_pad($m, 2,"0",STR_PAD_LEFT) : $m;
          $tbl .= "<td rel=".$year."-".$m."-".$d.">";
            $tbl .= "<div class='row'>";
              $tbl .= "<div id='in' class='column column-content'></div><div id='out' class='column column-content'></div>";
            $tbl .= "</div>";
          $tbl .= "</td>";
        }
        $tbl .= "</tr>";
      }
      $tbl .= "<tr style='background-color: #ccc;'><td></th><th colspan=11></br></td></tr>";
      // 12 - 22
      $tbl .= "<tr><th>Month</th><th colspan=11>Date</th></tr>";
      $tbl .= "<tr style='text-align: center;'>";
      $tbl .= "<th></th>";
      for($i = 12; $i <= 22; $i++) {
        $tbl .= "<th>".$i."</th>";
      }
      $tbl .= "</tr>";

      // IN or OUT LABELS
      $tbl .= "<tr style='text-align: center;'>";
      $tbl .= "<th></th>";
      for($i = 12; $i <= 22; $i++) {
        $tbl .= "<th><div class='row'><div class='column column-header'>IN</div><div class='column column-header'>OUT</div></div></th>";
      }
      $tbl .= "</tr>";

      // Months
      for ($m = 1; $m <= 12; $m++) {
        $tbl .= "<tr style='text-align: center;'>";
        $tbl .= "<th>".date("M", mktime(0, 0, 0, $m, 1, $year))."</th>";
        for($d = 12; $d <= 22; $d++){
          $d = strlen($d) == 1 ? str_pad($d, 2,"0",STR_PAD_LEFT) : $d;
          $m = strlen($m) == 1 ? str_pad($m, 2,"0",STR_PAD_LEFT) : $m;
          $tbl .= "<td rel=".$year."-".$m."-".$d.">";
            $tbl .= "<div class='row'>";
              $tbl .= "<div id='in' class='column column-content'></div><div id='out' class='column column-content'></div>";
            $tbl .= "</div>";
          $tbl .= "</td>";
        }
        $tbl .= "</tr>";
      }
      $tbl .= "<tr style='background-color: #ccc;'><td></th><th colspan=11></br></td></tr>";
      // 21- 30
      $tbl .= "<tr><th>Month</th><th colspan=9>Date</th></tr>";
      $tbl .= "<tr style='text-align: center;'>";
      $tbl .= "<th></th>";
      for($i = 23; $i <= 31; $i++) {
        $tbl .= "<th>".$i."</th>";
      }
      $tbl .= "</tr>";

      // IN or OUT LABELS
      $tbl .= "<tr style='text-align: center;'>";
      $tbl .= "<th></th>";
      for($i = 23; $i <= 31; $i++) {
        $tbl .= "<th><div class='row'><div class='column column-header'>IN</div><div class='column column-header'>OUT</div></div></th>";
      }
      $tbl .= "</tr>";

      // Months
      for ($m = 1; $m <= 12; $m++) {
        $tbl .= "<tr style='text-align: center;'>";
        $tbl .= "<th>".date("M", mktime(0, 0, 0, $m, 1, $year))."</th>";
        for($d = 23; $d <= 31; $d++){
          $d = strlen($d) == 1 ? str_pad($d, 2,"0",STR_PAD_LEFT) : $d;
          $m = strlen($m) == 1 ? str_pad($m, 2,"0",STR_PAD_LEFT) : $m;
          $tbl .= "<td rel=".$year."-".$m."-".$d.">";
            $tbl .= "<div class='row'>";
              $tbl .= "<div id='in' class='column column-content'></div><div id='out' class='column column-content'></div>";
            $tbl .= "</div>";
          $tbl .= "</td>";
        }
        $tbl .= "</tr>";
      }


    }
    $tbl .= "</table>";
    $tbl .= "</br></br></br>";
    echo $tbl;
  }

  if($_POST["calendarType"] == "Classcard"){
    $labels = ["Present", "Absent", "Tardy"];
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
            $tbl .= "<td>".$label."</td>";
          else
            $tbl .= "<td rel='$year-$m' data-label='$label'></td>";
        }
        $tbl .= "</tr>";
      }
    }
    $tbl .= "</tbody>";
    $tbl .= "</table>";
    $tbl .= "<br><br>";

    echo $tbl;
  }

  // build a calendar
}
