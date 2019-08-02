<?php
// include("/var/www/html/preschool/includes/DB.inc.php");
DEFINE("isDebugMode",false);
DEFINE("interval", 5); // 5 seconds
# arduino needs to send a hash tha will be identified by the webserver if its the arduino asking for an HTTP request


class SMS {

  public static function Sender() {
    $dateNow = new DateTime();
    $start = $dateNow->format("Y-m-d");
    $end = $dateNow->modify("+1 day")->format("Y-m-d");

    $q1 = "SELECT " .
                "proj_attendance.id, " .
                "proj_attendance.idnumber, " .
                "proj_attendance.isSent, " .
                "proj_attendance.gate, " .
                "proj_student.fname AS name, " .
                "proj_attendance.time, " .
                "proj_student.contact " .
              "FROM proj_student " .
              "LEFT JOIN proj_attendance " .
              "ON proj_student.idnumber = proj_attendance.idnumber " .
              "WHERE time >= \"$start 00:00:00\" AND time < \"$end 00:00:00\"";


      $records = Core\db::query(array($q1, array()));

      if(count($records) > 0) {
        if($records[0]["isSent"] == 0) {
          print_r($records[0]);
        }
      }


  }


  public static function Record(){
    $idnumber = $_GET["idnumber"];
    $direction = $_GET["dir"];

    $d = new DateTime();
    $dateNow = new DateTime();

    $start = $dateNow->format("Y-m-d");
    $end = $dateNow->modify("+1 day")->format("Y-m-d");

    /*
     *  To check if the length of number being sent is 7
     */
    if(!is_null($idnumber) && strlen($idnumber) == 7){
        if(self::studentExists($idnumber)){ // <---- ACCESS
          if($direction == "in") {
            $records = Core\db::query(array("SELECT gate, time FROM proj_attendance WHERE idnumber = ? and time >= \"$start 00:00:00\" AND time < \"$end\"", array($idnumber)));

            if(count($records) > 0) {
              /*
               *  Is the last record in the database for the student, an OUT?
               */
              $last = $records[count($records)-1];
              if($last["gate"] == "out") {
                /* INSERT an IN record IF:
                 * 1) Previous record is an OUT
                 * 2) Previous record is a time in the past,
                 */
                error_log("[**SMS_GATEWAY**] - $idnumber Previous record is an OUT. Insert an IN record.");
                if(strtotime($last["time"]) < strtotime($dateNow->format("Y-m-d"))) {
                  Core\db::query(
                          array("INSERT INTO proj_attendance(idnumber, gate, time, syear) VALUES (?, ?, ?, ?)",
                          array($idnumber, $direction, $d->format("Y-m-d H:i:s"), $d->format("Y"))));
                }
              } else {
                error_log("[**SMS_GATEWAY**] - $idnumber inserting failed. Previous record is not an OUT");
              }
            } else {
              /*
               *  There is no IN record for today. We insert
               */
              $command = Core\db::query(
                      array("INSERT INTO proj_attendance(idnumber, gate, time, syear) VALUES (?, ?, ?, ?)",
                      array($idnumber, $direction, $d->format("Y-m-d H:i:s"), $d->format("Y"))));
              if($command){
                error_log("[**SMS_GATEWAY**] - $idnumber has no IN record for today. INSERT!");
              }
            }
          }

          if($direction == "out") {
            $records = Core\db::query(array("SELECT gate, time FROM proj_attendance WHERE idnumber = ? and time >= \"$start 00:00:00\" AND time < \"$end\"", array($idnumber)));

            /*
             * INSERT an OUT record IF:
             * 1) There is no OUT record. Because its possible for a student to
             *    pass through the OUT gate without passing through the IN gate.
             *    e.g (students going in through cars)
             *
             * 2) Previous record is an IN record and its a time in the past.
             */
            if(count($records) > 0) {

              /*
               *  Is the last record in the database for the student, an IN?
               */
              $last = $records[count($records)-1];
              if($last["gate"] == "in") {
                // INSERT if the last record, is a time in the past.
                error_log("[**SMS_GATEWAY**] - $idnumber Previous record is an IN. Insert.");
                if(strtotime($last["time"]) < strtotime($dateNow->format("Y-m-d"))) {
                  Core\db::query(
                          array("INSERT INTO proj_attendance(idnumber, gate, time, syear) VALUES (?, ?, ?, ?)",
                          array($idnumber, $direction, $d->format("Y-m-d H:i:s"), $d->format("Y"))));
                }
              } else {
                error_log("[**SMS_GATEWAY**] - $idnumber inserting failed. Previous record is an OUT");
              }
            } else {
              /*
               * Its possible that a student didn't pass the entry gate
               * but will pass the exit gate, we record any way.
               */
              $command = Core\db::query(
                      array("INSERT INTO proj_attendance(idnumber, gate, time, syear) VALUES (?, ?, ?, ?)",
                      array($idnumber, $direction, $d->format("Y-m-d H:i:s"), $d->format("Y"))));
              if($command) {
                error_log("[**SMS_GATEWAY**] - $idnumber has no OUT record. INSERT.");
              }
            }
          }
        }
      }
  }

  private static function studentExists($idnumber){
    $studentRecord = Core\db::query(array("SELECT * from proj_student WHERE idnumber = ?", array($idnumber)));
    return (count($studentRecord) == 1) ? $studentRecord[0] : array();
  }


  // TODO: every 5 seconds 10 message
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
