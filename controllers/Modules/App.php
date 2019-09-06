<?php
#include("/var/www/html/controllers/Registry.php");  
include("/var/www/html/configs/gatekeeper.lorma.net.config.php");  
// include("/vagrant/html/configs/localhost.config.php");  
#include("/var/www/html/models/DB.php");  
class SMS {
  
  // TODO sent isSent = 1 if the message returned from the response is success.
  public static function Sender() {
    $studentdb = Core\Registry::get("config/database/studentdb");
    $dateNow = new DateTime();
    $startDate = $dateNow->format("Y-m-d");
    $endDate = $dateNow->modify("+1 day")->format("Y-m-d");
    $counter = 0;

    // Query for checking records of student today
    // IN
    $q_in = "SELECT " .
                "proj_attendance.id, " .
                "proj_attendance.idnumber, " .
                "proj_attendance.isSent, " .
                "proj_attendance.gate, " .
                $studentdb.".fname AS name, " .
                "proj_attendance.time, " .
                $studentdb.".contact " .
              "FROM ".$studentdb .
              " LEFT JOIN proj_attendance " .
              "ON ".$studentdb.".idnumber = proj_attendance.idnumber " .
              "WHERE gate = \"in\" AND time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\" AND contact != \"\"";

    // OUT
    $q_out = "SELECT " .
                "proj_attendance.id, " .
                "proj_attendance.idnumber, " .
                "proj_attendance.isSent, " .
                "proj_attendance.gate, " .
                $studentdb.".fname AS name, " .
                "proj_attendance.time, " .
                $studentdb.".contact " .
              " FROM ".$studentdb .
              " LEFT JOIN proj_attendance " .
              "ON ".$studentdb.".idnumber = proj_attendance.idnumber " .
              "WHERE gate = \"out\" AND time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\" AND contact != \"\"";


    // Query for check the last sent record if there are any
    // IN
    $q_last_in = "SELECT " .
                "proj_attendance.id, " .
                "proj_attendance.idnumber, " .
                "proj_attendance.isSent, " .
                "proj_attendance.time " .
              "FROM proj_attendance  " .
              "WHERE gate = \"in\" AND isSent = \"1\" AND time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\" " .
              "ORDER BY id DESC LIMIT 1";

    // OUT
    $q_last_out = "SELECT " .
              "proj_attendance.id, " .
              "proj_attendance.idnumber, " .
              "proj_attendance.isSent, " .
              "proj_attendance.time " .
            "FROM proj_attendance  " .
            "WHERE gate = \"out\" AND isSent = \"1\" AND time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 00:00:00\" " .
            "ORDER BY id DESC LIMIT 1";

    $in_records = Core\db::query(array($q_in));
    $out_records = Core\db::query(array($q_out));
    $in_last = Core\db::query(array($q_last_in)); // Look for the last record of IN
    $out_last = Core\db::query(array($q_last_out)); // Look for the last record of OUT
    $in_found = false;
    $out_found = false;

    // Always send the first IN or OUT record of the day, the time of the last sent
    // message will be the basis of the next records, whether they are within the
    // specified interval, if they are, do not send. If they are not, send them
    if(count($in_records) > 0) {
      if($in_records[0]["isSent"] == "0") {
        Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($in_records[0]["id"])));
        // Copy to sent table, we might not need to clean the attendance table for future records
        $cp = "INSERT INTO proj_sent(`idnumber`, `gate`, `isSent`, `time`,`syear`) 
                  SELECT `idnumber`, `gate`, `isSent`, `time`, `syear` 
                  FROM proj_attendance 
                  WHERE `id` = ".$in_records[0]["id"];
	      $cmd = Core\db::query(array($cp), array());

        $n = $in_records[0]["name"];
	      $t = new DateTime($in_records[0]["time"]);
	      $msg = "$n has passed the entrance gate at ".$t->format("Y-m-d h:i:sA");
	      $numbers = explode(";", $in_records[0]["contact"]);
	      foreach($numbers as $number) {
		       //self::sendSMS($number, $msg);
	      }
      }
    } else {
      $counter += 1;
    }

    $test = $in_records;
    foreach($test as $t) {
	    print_r($t); echo "<br>";
    }

    if(count($out_records) > 0) {
      if($out_records[0]["isSent"] == "0") {
        Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($out_records[0]["id"])));
        // Copy to sent table, we might not need to clean the attendance table for future records
        $cp = "INSERT INTO proj_sent(`idnumber`, `gate`, `isSent`, `time`,`syear`) 
                  SELECT `idnumber`, `gate`, `isSent`, `time`, `syear` 
                  FROM proj_attendance 
                  WHERE `id` = ".$in_records[0]["id"];
        $cmd = Core\db::query(array($cp), array());

	      $n = $out_records[0]["name"];
	      $t = new DateTime($out_records[0]["time"]);
	      $msg = "$n has passed the exit gate at ".$t->format("Y-m-d h:i:sA");
	      $numbers = explode(";", $out_records[0]["contact"]);
	      foreach($numbers as $number) {
		       //self::sendSMS($number, $msg);
	      }
      }
    } else {
      $counter += 1;
    }

    if(count($in_records) > 1) {
      foreach($in_records as $r) {
        if(count($in_last) > 0) {
          // Eval
          if($r["id"] >= $in_last[0]["id"]) {
            $l = new DateTime($in_last[0]["time"]); // Last
            $t = new DateTime($r["time"]); // Current Time
            $diff = $l->diff($t);
            if((intval($diff->format("%h")) >= 1) && !$in_found) { // BASIS FOR SENDING DELAY
              $cmd = Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($r["id"])));
              // Copy to sent table, we might not need to clean the attendance table for future records
              $cp = "INSERT INTO proj_sent(`idnumber`, `gate`, `isSent`, `time`,`syear`) 
                  SELECT `idnumber`, `gate`, `isSent`, `time`, `syear` 
                  FROM proj_attendance 
                  WHERE `id` = ".$r["id"];

              $cmd = Core\db::query(array($cp), array());
              $in_found = true;
  
              $n = $r["time"];
              $t = new DateTime($r["time"]);
              $msg = "$n has passed the entrace gate at ".$t->format("Y-m-d h:i:sA");
              $numbers = explode(";", $r["contact"]);
              foreach($numbers as $number) {
              	//self::sendSMS($number, $msg);
              }
	    }
          }
        }
      }
      $in_found = false;
    } else {
      $counter += 1;
    }

    if(count($out_records) > 1) {
      foreach($out_records as $r) {
        if(count($out_last) > 1) {
          if($r["id"] >= $out_last[0]["id"]) {
            $l = new DateTime($out_last[0]["time"]);
            $t = new DateTime($r["time"]);
            $diff = $l->diff($t);
            if((intval($diff->format("%h")) >= 1) && !$out_found) { // BASIS FOR SENDING DELAY
              echo "<br>";
              $cmd = Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($r["id"])));
              // Copy to sent table, we might not need to clean the attendance table for future records
              $cp = "INSERT INTO proj_sent(`idnumber`, `gate`, `isSent`, `time`,`syear`) 
                  SELECT `idnumber`, `gate`, `isSent`, `time`, `syear` 
                  FROM proj_attendance 
                  WHERE `id` = ".$r["id"];

              $cmd = Core\db::query(array($cp), array());
              
              $n = $r["name"];
              $t = new DateTime($r["time"]);
              $msg = "$n has passed the entrace gate at ".$t->format("Y-m-d h:i:sA");
              $numbers = explode(";", $r["contact"]);
              foreach($numbers as $number) {
              	//self::sendSMS($number, $msg);
              }
	    }
          }
        }
      }
      $out_found = false;
    } else {
      $counter += 1;
    }

    if($counter == 4) {
      // All checks have been done and there is nothing to be sent. Clean the table.
      // $cmd = Core\db::query(array("DELETE FROM proj_attendance WHERE `isSent` = 0", array()));
    }
  }

  // TODO: Send maximum of 200 messages in a minute
  static function sendSMS($number,$message,$params=array()){
        error_log("Sending....");
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
  	return strlen($number)==11 || strlen($number)==12;
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
}
//set_time_limit(60);
//for($i = 0; $i <= 59; ++$i) {
  //error_log("CHECKING FOR SMS QUEUE $i");
  SMS::Sender();
//  sleep(1);
//}
