<?php
$range = "";
$range = array("2018", "2019");
// if(isset($_SESSION["schoolYear"])) {
//   if($results = Core\db::query(array("SELECT name FROM proj_sy WHERE syear = ?", array($_SESSION["schoolYear"])))) {
//     $range = explode("-", $results[0]["name"]);
//   }
// }
// build calendar here
if(isset($_POST["calendarType"])){
  if($_POST["calendarType"] == "DTR"){
    $tbl = "";
    $tbl .= "<table id='attendanceSheet' class='table table-sm table-bordered' style='width: auto'>";
    foreach($range as $year){
      // CHANGE THIS
      $tbl .= "<tr id='attendanceYearLabel'><th colspan=17 style=''><h3>$year</h3></th></tr>";
      $tbl .= "<tr><th>Month</th><th colspan=16>Date</th></tr>";
      $tbl .= "<tr style='text-align: center;'>";
      $tbl .= "<th></th>";
      for($i = 1; $i <= 16; $i++) {
        $tbl .= "<th>".$i."</th>";
      }
      $tbl .= "</tr>";

      // Months
      for ($m = 1; $m <= 12; $m++) {
        $tbl .= "<tr style='text-align: center;'>";
        $tbl .= "<th>".date("F", mktime(0, 0, 0, $m, 1, $year))."</th>";
        for($d = 1; $d <= 16; $d++){
          $d = strlen($d) == 1 ? str_pad($d, 2,"0",STR_PAD_LEFT) : $d;
          $m = strlen($m) == 1 ? str_pad($m, 2,"0",STR_PAD_LEFT) : $m;
          $tbl .= "<td rel=".$year."-".$m."-".$d."></td>";
        }
        $tbl .= "</tr>";
      }
      $tbl .= "<tr><td colspan=17 style='background-color: #fff;'></td></tr>";
      $tbl .= "<tr style='text-align: center;'>";
      $tbl .= "<th></th>";
      for($i = 17; $i <= 32; $i++) {
        if($i <= 31)
          $tbl .= "<th>".$i."</th>";
        else
          $tbl .= "<th></th>";

      }
      $tbl .= "</tr>";

      // Months
      for ($m = 1; $m <= 12; $m++) {
        $tbl .= "<tr style='text-align: center;'>";
        $tbl .= "<th>".date("F", mktime(0, 0, 0, $m, 1, $year))."</th>";
        for($d = 17; $d <= 32; $d++){
          if($d <= 31){
            $d = strlen($d) == 1 ? str_pad($d, 2,"0",STR_PAD_LEFT) : $d;
            $m = strlen($m) == 1 ? str_pad($m, 2,"0",STR_PAD_LEFT) : $m;
            $tbl .= "<td rel=".$year."-".$m."-".$d."></td>";
          } else {
            $tbl .= "<th></th>";
          }
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
