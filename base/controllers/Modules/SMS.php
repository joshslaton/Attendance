<?php
namespace Core;
// include("/var/www/html/preschool/includes/DB.inc.php");
DEFINE("isDebugMode",true);
DEFINE("interval", 86400);
# arduino needs to send a hash tha will be identified by the webserver if its the arduino asking for an HTTP request


class SMS {

  public static function Sender() {
    $query = "SELECT " .
                "proj_attendance.id, " .
                "proj_attendance.idnumber, " .
                "proj_attendance.gate, " .
                "CONCAT(
                  proj_student.fname,
                  \"\") AS name, " .
                "proj_attendance.time, " .
                "proj_student.contact " .
              "FROM proj_student " .
              "LEFT JOIN proj_attendance " .
              "ON proj_student.idnumber = proj_attendance.idnumber " .
              "WHERE isSent = 0";

    $results = db::query(array($query, array()));

    if(!empty($results)) {
      foreach($results as $result) {

        switch($result["gate"]) {
          case "in": $gate = "entrance"; break;
          case "out": $gate = "exit"; break;
          default: $gate = "teszt"; break;
        }

        if($contacts = explode(",", $result["contact"])) {
          // If there are one or more valid numbers
          if(count($contacts) > 1) {
            foreach($contacts as $contact) {
              if(SMS::isMobileNumber(SMS::cleanNumber($contact))) {
                $contact = SMS::cleanNumber($contact);
                $message = $result["name"]. " has passed the ". strtoupper($gate." gate") ." at ".$result["time"];
                // echo "Length of message: " . strlen($message);
                // echo "<br>";
                SMS::sendSMS($contact,$message);
              }
            }
          }

          // If there is only one valid number
          if(count($contacts) == 1) {
            foreach($contacts as $contact) {
              if(SMS::isMobileNumber(SMS::cleanNumber($contact))) {
                $contact = SMS::cleanNumber($contact);
                $message = $result["name"]. " has passed the ". strtoupper($gate." gate") ." at ".$result["time"];
                // echo "Length of message: " . strlen($message);
                // echo "<br>";
                SMS::sendSMS($contact,$message);
              }
            }
          }
        }
      }
    }
  }

  // Record as much but only send one, the most early time recorded among
  // Data privacy ReflectionFunctionAbstract
  // what do you collect
  // what do you do to the data
  // disposal of data
  // what do you do when if theres a problem
  // id number, mobile numbers, purpose
  // purpose of system
  // end of validity
  // if(db::query(array($query, array($idnumber, $d, $dateNow->format("Y-m-d H:i:s"), $dateNow->format("Y"))))) {
  //   echo "Insert success!";
  // }
  250525923
  public static function Record(){
    $idnumber = $_GET["idnumber"];
    $d = $_GET["dir"];
    $dateNow = new \DateTime();
    if(!is_null($idnumber) && strlen($idnumber) == 7){
        if(self::studentExists($idnumber)){
          // IN
          if($d == "in"){
            $timeRecords = db::query(array("SELECT time FROM proj_attendance WHERE idnumber = ?", array($idnumber)));
            $prev
            if(count($timeRecords) > 0) {
              // Is the record, from today?
              foreach($timeRecords as $timeRecord) {
                echo $timeRecord["time"];
                echo "<br>";
              }
            }

          }

          // OUT
          if($d == "out"){
            $timeRecord = db::query(array("SELECT * FROM proj_attendance WHERE idnumber = ?", array($idnumber)));
          }

        }
      }
  }

  private static function studentExists($idnumber){
    $studentRecord = db::query(array("SELECT * from proj_student WHERE idnumber = ?", array($idnumber)));
    return (count($studentRecord) == 1) ? $studentRecord[0] : array();
  }


  // every 5 seconds 10 message
  static function sendSMS($number,$message,$params=array()){
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
        print_r($data);
        echo "<br>";
        // $curlopt = array(
        //     CURLOPT_URL => 'https://lcaccess2.lorma.edu/sms/',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS =>
        //     (http_build_query($data)),
        //     CURLOPT_HTTPHEADER => array(
        //         "Content-Type: application/x-www-form-urlencoded",
        //         "cache-control: no-cache"
        //     ),
        //     #CURLOPT_SSL_VERIFYHOST => false,
        //     #CURLOPT_SSL_VERIFYPEER => false,
        // );
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


        // print_r('https://lcaccess2.lorma.edu/sms/?'.http_build_query($data));


        print_r(array(
            'response' => strpos($response,"{")!==false
                ? json_decode($response)
                : $response,
            'error' => $error
            ));
            echo "<br>";
            echo "<br>";

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

SMS::Record();
// SMS::Sender();
