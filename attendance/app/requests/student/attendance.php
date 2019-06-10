<?php
// calendar
// X-axis = Months
// Y-axis = Days


// Classcard Style
function build($year) {
  $tbl = "<table id='attendanceSheet' class='' style='width: auto' data-script='test'>";
  $tbl .= "<tr style='text-align: center;'>";
  $tbl .= "<th></th>";
  for($i = 1; $i <= 31; $i++) {
    $tbl .= "<th>".$i."</th>";
  }
  $tbl .= "</tr>";

  // Months
  for ($m = 1; $m <= 12; $m++) {
    $tbl .= "<tr style='text-align: center; padding: 5px;'>";
    $tbl .= "<th>".date("F", mktime(0, 0, 0, $m, 1, $year))."</th>";
    for($d = 1; $d <= 31; $d++){
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
