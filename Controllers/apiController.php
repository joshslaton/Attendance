<?php

class apiController extends Controller{

  function index() {
    include("../Models/Attendance.php");
    $attendance  = new Attendance();

    // What are the records that is not evaluated yet
    $records_to_check = $attendance->query_all_records();
    $prev = array();

    echo "Records to evaluate: " . count($records_to_check) . "<br>";
    foreach($records_to_check as $index => $r) {
      $last_record = $attendance->query_last_record_of_student($r["idnumber"], $r["gate"]);
      // print_r($last_record); echo "<br>";
      if(count($last_record) == 0) {
        $attendance->update_record($r["id"], array("isSent = 1", "isEval = 1"));
      }
      else {
        // Check
        $last = new DateTime($last_record[0]["time"]);
        $current = new DateTime($r["time"]);
        $diff = $last->diff($current);
        if(intval($diff->format("%h")) >= 1) {
          $attendance->update_record($r["id"], array("isSent = 1", "isEval = 1"));
        }else {
          $attendance->update_record($r["id"], array("isEval = 1"));
        }
      }
    }
    // self::createTableFromArray($attendance->query_all_records_for_display());
  }

  function record($params="") {
    $params = str_replace("?", "", $params);
    $params = explode("&", $params);
    $vars = array();
    foreach($params as $p) {
      $p = explode("=", $p);
      $vars[$p[0]] = $p[1];
    }
    if(isset($vars["idnumber"]) &&
    isset($vars["dir"])) {
      include("../Models/Attendance.php");
      $d = new DateTime();
      $schoolYear = "2019"; // This should be in a config file
      $attendance = new Attendance();
      $attendance->insert(
        $vars["idnumber"],
        $vars["dir"],
        $d->format("Y-m-d H:i:s"),
        $schoolYear
      );
    }
  }

  private function createTableFromArray($arr) {
    echo "<table class=\"table table-sm\">";
    foreach($arr as $i => $tr) {
      if($tr["isSent"] == 1) {
        echo "<tr style=\"background-color: #00FF00;\">";
        foreach($tr as $td) {
          if($td instanceof DateTime) {
            echo "<td>" . $td->format("Y-m-d h:i:sa") . "</td>";
          }else {
              echo "<td>" . $td ."<td>";
          }
        }
        echo "</tr>";
      } elseif($tr["isEval"] == 1) {
        echo "<tr style=\"background-color: #FFFF00;\">";
        foreach($tr as $td) {
          if($td instanceof DateTime) {
            echo "<td>" . $td->format("Y-m-d h:i:sa") . "</td>";
          }else {
              echo "<td>" . $td ."<td>";
          }
        }
        echo "</tr>";
      }else {
        echo "<tr>";
        foreach($tr as $td) {
          if($td instanceof DateTime) {
            echo "<td>" . $td->format("Y-m-d h:i:sa") . "</td>";
          }else {
              echo "<td>" . $td ."<td>";
          }
        }
        echo "</tr>";
      }
    }
    echo "</table><br>";
  }
}
