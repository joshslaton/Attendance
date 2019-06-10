<?php
// calendar
// X-axis = Months
// Y-axis = Days

// PDF
// Classcard Style
function build($year) {
  $tbl = "<table id='attendanceSheet' class='table table-sm table-bordered' style='width: auto' data-script='test'>";
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
  $tbl .= "<tr><td colspan=16 style='background-color: #ccc;'></td></tr>";
  $tbl .= "<tr style='text-align: center;'>";
  $tbl .= "<th></th>";
  for($i = 17; $i <= 31; $i++) {
    $tbl .= "<th>".$i."</th>";
  }
  $tbl .= "</tr>";

  // Months
  for ($m = 1; $m <= 12; $m++) {
    $tbl .= "<tr style='text-align: center;'>";
    $tbl .= "<th>".date("F", mktime(0, 0, 0, $m, 1, $year))."</th>";
    for($d = 17; $d <= 31; $d++){
      $d = strlen($d) == 1 ? str_pad($d, 2,"0",STR_PAD_LEFT) : $d;
      $m = strlen($m) == 1 ? str_pad($m, 2,"0",STR_PAD_LEFT) : $m;
      $tbl .= "<td rel=".$year."-".$m."-".$d."></td>";
    }
    $tbl .= "</tr>";
  }
  $tbl .= "</table>";

  return $tbl;
}

echo build("2019");
