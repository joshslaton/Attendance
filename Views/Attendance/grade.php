<?php

$date3 = new DateTime();
$html = "";
$html .= "<div id=\"pageBody\">";
  $html .= "<form method=\"post\" action=\"\">";
    $html .= "<fieldset>";
      // Grade Level
      $html .= "<label for=\"gradeLevels\">Grade Level: </label>";
      $html .= "<select id=\"gradeLevels\" name=\"gradeLevel\" style=\"margin-right: 10px;\">";
        foreach($grade as $g) {
          $html .= "<option>" . $g["ylevel"] . "</option>";
        }
      $html .= "</select>";

      // Month
      $html .= "<label for=\"month\">Month: </label>";
      $html .= "<select id=\"month\" name=\"month\" style=\"margin-right: 10px;\">";
      $html .= "<option selected>" . $date3->format("m") . "</option>";
        for($m = 1; $m <= 12; $m++) {
          $date = new DateTime("2019-$m-1");
          $html .= "<option>" . $m . "</option>";
          // $html .= "<option>" . $date->format("F") . "</option>";
        }
      $html .= "</select>";

      $html .= "<label for=\"year\">Year: </label>";
      $html .= "<select id=\"year\" name=\"year\" style=\"margin-right: 10px;\">";
        $html .= "<option>2019</option>";
        $html .= "<option>2018</option>";
      $html .= "</select>";

      // Submit
      $html .= "<input type=\"submit\" value=\"Submit\">";
      if(isset($gradeLevel) && isset($month) && isset($year)) {
        if($gradeLevel != "" && $month != "" && $year != "") {
          $html .= "<input type=\"submit\" value=\"Print This\">";
        }
      }
    $html .= "</fieldset>";
  $html .= "</form>";

  // Table
  if(isset($gradeLevel) && isset($month) && isset($year)) {
    if($gradeLevel != "" && $month != "" && $year != "") {
      $date = new DateTime("$year-$month-1");
      $daysInMonth = $date->format("t");

      $html .= "<br><br><table class=\"table table-sm\">";

      // Header
      $html .= "<tr>";
      $html .= "<th class=\"center\" colspan=".intval($daysInMonth+2).">Attendance Report per Grade</th>";
      $html .= "</tr>";
      $html .= "<tr>";
      $html .= "<th class=\"center\" colspan=".intval($daysInMonth+2).">For the month of " . $date->format("F") . " $year</th>";
      $html .= "</tr>";
      $html .= "<tr>";
      $html .= "<th class=\"center\" colspan=".intval($daysInMonth+2).">$gradeLevel</th>";
      $html .= "</tr>";
      $html .= "<tr>";
      $html .= "<th class=\"center\" colspan=".intval($daysInMonth+2).">&nbsp;</th>";
      $html .= "</tr>";

      // Day
      $html .= "<tr>";
      $html .= "<th colspan=2></th>";
      for($d = 1; $d <= $daysInMonth; $d++) {
        $date2 = new DateTime("$year-$month-$d");
        $day = $date2->format("D");
        $html .= "<th>$day[0]</th>";
      }
      $html .= "</tr>";

      // Date
      $html .= "<tr>";
      $html .= "<th>ID Number</th>";
      $html .= "<th>Last Name</th>";
      for($d = 1; $d <= $daysInMonth; $d++) {
        $html .= "<th>$d</th>";
      }
      $html .= "</tr>";
      foreach($students as $student) {
        $html .= "<tr>";
        $html .= "<td>" . $student["idnumber"] . "</td>";
        $html .= "<td>" . strtoupper($student["lname"]) . "</td>";
        for($d = 1; $d <= $daysInMonth; $d++) {
          if(isset($records[$student["idnumber"]][$d])) {
            if($records[$student["idnumber"]][$d] > 0) {
              $html .= "<td>O</td>";
            }
          }else {
            $html .= "<td></td>";
          }
        }

        $html .= "</tr>";
      }
      $html .= "</table>";
    }
  }
  echo $html;
  ?>
</div>
