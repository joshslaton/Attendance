<?php
if(isset($_POST["optionMonth"]) && isset($_POST["optionYear"])) {
  $year = $_POST["optionYear"];
  $month = $_POST["optionMonth"];
  $d = new DateTime("$year-$month-01");
  $daysInMonth=cal_days_in_month(CAL_GREGORIAN,$month,$year);
  $columns = array(
    "Day", "", "In", "Out", "Remarks"
  );

  $data = array();
  // Convert time in the array to DateTime Object
  foreach($attendanceList as $k => $v) {
    $attendanceList[$k]["time"] = new DateTime($v["time"]);
  }
  for($day = 1; $day <= $daysInMonth; $day++) {
    $data[strval($day)] = array();
  }
  for($day = 1; $day <= $daysInMonth; $day++) {
    foreach($attendanceList as $al) {
      $d1 = new DateTime("$year-$month-$day"); // Current iteration
      if(strtotime($d1->format("Y-m-d")) == strtotime($al["time"]->format("Y-m-d"))) {
        array_push($data[strval($day)], $al);
      }
    }
  }

  $html = "";
  // Attendance Table
  $html .= "<table id=\"attendanceCard\" class=\"table table-sm\">";
  // Student Information
  if($studentInfo) {
    $fullname = strtoupper($studentInfo["lname"]) . ", " . $studentInfo["fname"] . " " . $studentInfo["mname"];
    $html .= "<tr>";
    $html .= "<td colspan=4><h4>Name: $fullname</h4></td><td colspan=1></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td colspan=4><h4>ID Number: " . $studentInfo["idnumber"] . "</h4></td><td colspan=1></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td colspan=4><h4>Class: " . $studentInfo["ylevel"] . "</h4></td><td colspan=1></td>";
    $html .= "</tr>";
  }

  // Attendance Information
  $html .= "<tr><th colspan=5><center>" . $d->format("F") . " $year</center></th></tr>";
  $html .= "<tr>";
  foreach($columns as $column) {
    $html .= "<th>" . strtoupper($column) . "</th>";
  }
  $html .= "</tr>";
  foreach($data as $k => $v) {
    if(count($v) > 0) { // column with entry
      if(count($v) == 1){
        $html .= "<tr>";
          foreach($columns as $index => $c) {
            if($index == 0) {
              $day = (new DateTime("$year-$month-$k"))->format("D");
              $html .= "<td>$day</td>";
            } elseif($index == 1) {
              $html .= "<td>$k</td>";
            }else {
              if(strtolower($c) == "in") {
                $html .= "<td>" . $v[0]["time"]->format("H:i:sA") . "</td>";
              } elseif(strtolower($c) == "out") {
                $html .= "<td></td>";
              } elseif(strtolower($c) == "remarks") {
                $html .= "<td></td>";
              }else {
                $html .= "<td></td>";
              }
            }
          }
          $html .= "</tr>";
        }else {
          $html .= "<tr>";
            foreach($columns as $index => $c) {
              if($index == 0) {
                $day = (new DateTime("$year-$month-$k"))->format("D");
                $html .= "<td>$day</td>";
              } elseif($index == 1) {
                $html .= "<td>$k</td>";
              }else {
                if(strtolower($c) == "in") {
                  $html .= "<td>";
                    foreach($v as $w) {
                      if($w["gate"] == "in") {
                        $html .= $w["time"]->format("h:i:s A") . "<br>";
                      }
                    }
                    $html .= "</td>";
                  } elseif(strtolower($c) == "out") {
                    $html .= "<td>";
                      foreach($v as $w) {
                        if($w["gate"] == "out") {
                          $html .= $w["time"]->format("h:i:s A") . "<br>";
                        }
                      }
                      $html .= "</td>";
                    } elseif(strtolower($c) == "remarks") {
                      $html .= "<td></td>";
                    }else {
                      $html .= "<td></td>";
                    }
                  }
                }
                $html .= "</tr>";
              }
            }else {
              $html .= "<tr>";
                foreach($columns as $index => $c) {
                  if($index == 0) {
                    $day = (new DateTime("$year-$month-$k"))->format("D");
                    $html .= "<td>$day</td>";
                  }elseif($index == 1) {
                    $html .= "<td>$k</td>";
                  }else {
                    $html .= "<td></td>";
                  }
                }
                $html .= "</tr>";
              }
            }
            $html .= "</table>";
            echo $html;
}
?>
