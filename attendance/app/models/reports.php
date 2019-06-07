<?php
namespace Core;

class Reports {

  function getReportByStudent($month, $year, $viewStyle) {

    // DTR
    if(isset($_POST["viewStyle"]) == "CMatrix") {

    }

    // Calendar Matrix
    if(isset($viewStyle) == "DTR") {
      $monthName = date("F", mktime(0, 0, 0, $month, 1, $year));
      $daysInmonth = Calendar::daysInMonth($month, $year);
      $t = "<table class='table table-sm' style='display: inline-block; width: auto; margin: 10px; vertical-align: top;'>";
      $t .= "<tr><th colspan=3>$monthName $year</th></tr>";
      $t .= "<tr><th>#</th><th>Time</th><th>Remarks</th></tr>";

      for($j = 1; $j <= $daysInmonth; $j ++) {
        $j = strlen($j) == 1 ? "0".$j : $j;
        $m = strlen($month) == 1 ? "0".$month : $month;
        $day = date("D", mktime(0, 0, 0, $month, $j, $year));
        $t .= "<tr rel='$year-$m-$j'>";
        $t .= "<td>$j $day</td>";
        $t .= "<td rel='time'></td>";
        $t .= "<td rel='remarks'></td>";
        $t .= "</tr>";
      }

      $t .= "</table>";
      return $t;
    }
  }
}
0
