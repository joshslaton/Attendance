<?php

class apiController extends Controller{

  function index() {

  }

  function evaluate($data) {
    include("../Models/DB.php");
    include("../Models/Attendance.php");
    include("../Models/Student.php");
    $a = new Attendance();
    $s = new Student();
    if($s->exists("idnumber", $data["idnumber"])) {
        $r = $a->query_last_record($data["idnumber"], $data["gate"]);
        if($r) {
          $r = $r[0];
          $now = new DateTime();
          $inDB = new DateTime($r["time"]);
          $diff = $data["time"]->diff($inDB);
          if(intval($diff->format("%h")) >= 1) {
            // Sender
            if($r["contact"] != "") {
              $contact = explode(";", $r["contact"]);
              if(count($contact) > 1) {
                foreach($contact as $c) {
                  if(self::numberonly($c) && self::isMobileNumber($c)) {
                    $number = self::cleanNumber($c);
                    $gate = $r["gate"] == "in" ? "entrance" : "exit";
                    $msg = $r["fname"] . " has passed the $gate gate at " . $r->format("Y-m-d h:i:s A");
                    // self::sendSMS($r["idnumber"], $number, $msg);
                  }
                }
              }else {
                if(self::numberonly($r["contact"]) && self::isMobileNumber($r["contact"])) {
                  $number = self::cleanNumber($r["contact"]);
                  $gate = $r["gate"] == "in" ? "entrance" : "exit";
                  $msg = $r["fname"] . " has passed the $gate gate at " . $data["time"]->format("Y-m-d h:i:s A");
                  // self::sendSMS($r["idnumber"], $number, $msg);
                }
              }
            }
            $a->insert($data["idnumber"], $data["gate"], $data["time"]->format("Y-m-d H:i:s"), $data["schoolYear"], 1, 1);
          }else {
            if(intval($diff->format("%i")) >= 1) {
              $a->insert($data["idnumber"], $data["gate"], $data["time"]->format("Y-m-d H:i:s"), $data["schoolYear"], 1, 0);
            }
          }
        }else {
            // Send
          $studentInfo = $s->studentGetInfo($data["idnumber"]);
          if($studentInfo["contact"] != "") {
            $contact = explode(";", $studentInfo["contact"]);
            if(count($contact) > 1) {
              foreach($contact as $c) {
                if(self::numberonly($c) && self::isMobileNumber($c)) {
                  $number = self::cleanNumber($c);
                  $gate = $data["gate"] == "in" ? "entrance" : "exit";
                  $msg = $studentInfo["fname"] . " has passed the $gate gate at " . $data["time"]->format("Y-m-d h:i:s A");
                  // self::sendSMS($r["idnumber"], $number, $msg);
                }
              }
            }else {
              if(self::numberonly($studentInfo["contact"]) && self::isMobileNumber($studentInfo["contact"])) {
                $number = self::cleanNumber($studentInfo["contact"]);
                $gate = $data["gate"] == "in" ? "entrance" : "exit";
                $msg = $studentInfo["fname"] . " has passed the $gate gate at " . $data["time"]->format("Y-m-d h:i:s A");
                // self::sendSMS($r["idnumber"], $number, $msg);
              }
            }
          }
          $a->insert($data["idnumber"], $data["gate"], $data["time"]->format("Y-m-d H:i:s"), $data["schoolYear"], 1, 1);
        }
    }
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
    $response = curl_exec($curl);
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
