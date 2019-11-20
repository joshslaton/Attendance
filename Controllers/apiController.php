<?php

class apiController extends Controller{

  function index() {

  }

  function evaluate() {
    include("../Models/Attendance.php");
    $attendance  = new Attendance();

    // What are the records that is not evaluated yet
    $records_to_check = $attendance->query_all_records();
    $prev = array();

    echo "Records to evaluate: " . count($records_to_check) . "<br>";
    foreach($records_to_check as $index => $r) {
      $last_record = $attendance->query_last_record_of_student($r["idnumber"], $r["gate"]);
      // print_r($r); echo "<br>";
      if(count($last_record) == 0) {
        $attendance->update_record($r["id"], array("isSent = 1", "isEval = 1"));
      }
      if(count($last_record) > 0) {
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

  function sender() {
    //set_time_limit(60);
    //for($t = 0; $t <= 59; $t++) {
    $d = new DateTime();
    include_once("../Models/Attendance.php");
    $attendance  = new Attendance();
    $record_to_send = $attendance->query_record_to_send();
    foreach($record_to_send as $r) {
      // within before the hour
      $current = new DateTime($r["time"]);
      $diff = $current->diff($d);
      echo $r["id"] . " " . $current->format("Y-m-d h:i:s A") . " - " . $d->format("Y-m-d h:i:s A") . "<br>";
      if(intval($diff->format("%h") <= 1)) {
        if($r["contact"] != "") {
          $contact = explode(";", $r["contact"]);
          if(count($contact) > 1) {
            foreach($contact as $c) {
              if(self::numberonly($c) && self::isMobileNumber($c)) {
                $number = self::cleanNumber($c);
                $gate = $r["gate"] == "in" ? "entrance" : "exit";
                $msg = $r["fname"] . " has passed the $gate gate at " . $current->format("Y-m-d h:i:s A");
                self::sendSMS($r["idnumber"], $number, $msg);
              }
            }
          }else {
            if(self::numberonly($r["contact"]) && self::isMobileNumber($r["contact"])) {
              $number = self::cleanNumber($r["contact"]);
              $gate = $r["gate"] == "in" ? "entrance" : "exit";
              $msg = $r["fname"] . " has passed the $gate gate at " . $current->format("Y-m-d h:i:s A");
              self::sendSMS($r["idnumber"], $number, $msg);
            }
          }
        }
        $attendance->update_record($r["id"], array("isSent = 2"));
      }else {
        $attendance->update_record($r["id"], array("isSent = 2"));
      }
    }
    //sleep(1);
    //}
  }
  private function createTableFromArray($arr) {
    echo "<table class=\"table table-sm\">";
    foreach($arr as $i => $tr) {
      if($tr["isSent"] == 2) {
        echo "<tr style=\"background-color: #80f2e3;\">";
        foreach($tr as $td) {
          if($td instanceof DateTime) {
            echo "<td>" . $td->format("Y-m-d h:i:s A") . "</td>";
          }else {
            echo "<td>" . $td ."<td>";
          }
        }
        echo "</tr>";
      } elseif($tr["isSent"] == 1) {
        echo "<tr style=\"background-color: #00FF00;\">";
        foreach($tr as $td) {
          if($td instanceof DateTime) {
            echo "<td>" . $td->format("Y-m-d h:i:s A") . "</td>";
          }else {
            echo "<td>" . $td ."<td>";
          }
        }
      } elseif($tr["isEval"] == 1) {
        echo "<tr style=\"background-color: #FFFF00;\">";
        foreach($tr as $td) {
          if($td instanceof DateTime) {
            echo "<td>" . $td->format("Y-m-d h:i:s A") . "</td>";
          }else {
            echo "<td>" . $td ."<td>";
          }
        }
        echo "</tr>";
        echo "</tr>";
      }else {
        echo "<tr>";
        foreach($tr as $td) {
          if($td instanceof DateTime) {
            echo "<td>" . $td->format("Y-m-d h:i:s A") . "</td>";
          }else {
            echo "<td>" . $td ."<td>";
          }
        }
        echo "</tr>";
      }
    }
    echo "</table><br>";
  }

  // TODO: Send maximum of 200 messages in a minute
  public static function sendSMS($idnumber, $number,$message,$params=array()){
    error_log("[".$idnumber."] $number - $message<br>");
    // print_r("[".$idnumber."] $number - $message<br>");
    $number = self::cleanNumber($number);
    $curl = curl_init();
    $data = array(
      "action" => "send",
      "number" => $number,
      "message" => $message,
      "by" => "SJRFID",
      "name" => "SJRFID",
      "key" => sha1($number.sha1($message)."SJRFID"."SJRFID"),
      "id" => "1000",
    );
    $curlopt = array(
      CURLOPT_URL => "https://lcaccess2.lorma.edu/sms/",
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_VERBOSE => true,
      CURLOPT_POSTFIELDS =>
      (http_build_query($data)),
    );
    curl_setopt_array($curl,$curlopt);
    $response = "nani";
    // $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
    var_dump(array(
      'response' => strpos($response,"{")!==false
        ? json_decode($response)
        : $response,
        'error' => $error
      ));
      return array(
        'response' => strpos($response,"{")!==false
          ? json_decode($response)
          : $response,
          'error' => $error
        );
      }

      public static function isMobileNumber($number){
        return strlen($number)==10 || strlen($number)==11 || strlen($number)==12;
      }

      // Change 63 to 0.
      public static function cleanNumber($number){
        $number = self::numberonly($number, "digits");
        $number = substr($number,0,3 == "630") ? "63".substr($number,3) : $number;
        return strlen($number) == 10 ? "63".$number : $number;
        //return strlen($number)==12 ?
      }

      private static function isSetParam($param, $paramArray=array()){
        if(is_array($param)){
          return in_array($param, $paramArray, true) || array_key_exists($param, $paramArray);
        }
        return $param == $paramArray;
      }

      private static function numberonly($str, $params=array()){
        if(is_array($str)){
          $keys = array_keys($str);
          $str = count($keys) <= 0 ? 0 : $str[$keys[0]];
        }
        $preg = self::isSetParam("digits", $params) ? "/[^0-9]/" : "/[^0-9\.\-]/"; // FALSE
        $retstr = preg_replace($preg,"",$str);
        $retstr = $retstr == '' ? 0 : $retstr;
        return self::isSetParam('string',$params) ? $retstr."" : $retstr;
      }

      private static function isValid($arr) {
        // echo "Checking: ".$arr["fname"];
        // echo "Checking: ". $arr["fname"] . "<br>";
        foreach($arr as $a) {
          if(!$a == "") {
            continue;
          }else {
            return false;
          }
        }
        return true;
      }

    }
