<?php
include("/var/www/html/preschool/includes/DB.inc.php");
DEFINE("isDebugMode",true);
DEFINE("cooldown", 1);
# arduino needs to send a hash tha will be identified by the webserver if its the arduino asking for an HTTP request

class SMS {
  public function init(){
    $source = $_SERVER["REMOTE_ADDR"];
    $idnumber = $_GET["idnumber"];
    // var_dump($source, $idnumber);
    $currentMonth = date("m");
    $currentDay = date("d");
    $currentYear = date("Y");
    $direction = $this->setDirection($source);

    if(isset($idnumber) && strlen($idnumber) == 7){
        if($studentinfo = $this->checkIfStudentExists($idnumber)){
          // TODO: $x is returning 2-dimensional array
          if($x = $this->studentHasEntryToday($idnumber, $currentMonth, $currentDay, $currentYear, $direction)){
              if(((strtotime(date("H:i:s"))) - (strtotime(date("H:i:s", strtotime($x[0]["time_recorded"]))))) > cooldown){
                echo (isDebugMode ? "[+] Student has existing log today" : "" );
                $this->recordToLogs($studentinfo["idnumber"], $studentinfo["name"], $studentinfo["contact1"], $studentinfo["contact2"], $x[0]["direction"]);
            }else{
              echo (isDebugMode ? "[-] Tapped recently, please wait for ".(cooldown)." seconds" : "" );
            }

          }else{
            # Since no student has record for today let direction be the arduino device that requested it -> setDirection()
            echo (isDebugMode ? "[+] New entry for student for today." : "" );
            echo (isDebugMode ? " <br>[+] Student has passed the gate at.".date("h:i:sa") : "" );
            $this->recordToLogs($studentinfo["idnumber"], $studentinfo["name"], $studentinfo["contact1"], $studentinfo["contact2"], $direction);
          }
        }
      }
  }

  private function recordToLogs($idnumber, $studentname, $contact1, $contact2, $dir) {
    echo (isDebugMode ? "<br>[+] Logging Student ID: ".$idnumber : "</br>" );
    $currenttime_stamp = date("Y-m-d H:i:s");
    $message = $studentname. " has passed the gate at ".date("F-d-Y h:i:sa", strtotime($currenttime_stamp));
    $db = new DB("192.168.8.222","kiosk","kiosk","preschool_gatekeeper");
    if($db) {

        if($db->query('INSERT INTO gatekeeper_in ( idnumber, message, time_recorded, direction, isSent ) VALUES (?, ?, ?, ?, ?)', $idnumber ,$message, $currenttime_stamp, $dir, 0)){

          echo "<br>[+] Insert success!<br>";
          echo "[+] ".$message;

        // LOG
        $msg = $name. " has passed the gate at ".date("h:i:sa");

          //echo $msg."<br>";
          if($this->isMobileNumber($this->cleanNumber($contact1))){
            $number = $this->cleanNumber($contact1);
          }else{
            echo (isDebugMode ? "<br>[-] Not a mobile number" : "" );
          }

          if($this->isMobileNumber($this->cleanNumber($row["contact2"]))){
            $number = $this->cleanNumber($row["contact2"]);
          }else{
            echo (isDebugMode ? "<br>[-] Not a mobile number" : "" );
          }

        }
      }
    }



private function checkIfStudentExists($idnumber){
    $db = new DB("192.168.8.222","kiosk","kiosk","preschool_gatekeeper");
    $studentRecord = $db->query("SELECT * from preschool WHERE idnumber = ".$idnumber."")->fetchArray();
    if($studentRecord){
      return $studentRecord;
    }else{
      echo (isDebugMode ? "<br>[-] Student ID: ".$idnumber." has no record in the database" : "" );
      return false;
    }

  }

  private function studentHasEntryToday($idnumber, $m, $d, $y, $dir){
      $db = new DB("192.168.8.222","kiosk","kiosk","preschool_gatekeeper");
      if($db){
        $q = "SELECT * from gatekeeper_in WHERE idnumber = ".$idnumber." && MONTH(time_recorded) = ".$m." && DAY(time_recorded) = ".$d." && YEAR(time_recorded) = ".$y." && direction = '".$dir."' ORDER BY id DESC LIMIT 1";
        if($studentRecord = $db->query($q)->fetchAll()){
          return $studentRecord;
        }
      }
  }

  protected function sendSMS2($from,$number,$message){
  	$curl = curl_init();
  	$split = "[{]}}]";
  	$msg = explode($split,wordwrap($message,125,$split,true));
    //echo (isDebugMode ? "<br>[+] Sending for number: ".$number : "" );
  	foreach($msg as $message){

  		$url = "http://122.54.156.235/playsms/index.php";
  		$parameters = array(
  			'app'=>'ws',
  			'h'=>'6712b7f143b1e4004613cca837d27a16',
  			'u'=>$from,
  			'msg'=>trim($message),
  			'to'=>$number,
  			'op'=>'pv'
  		);
  		$url = $url .'?'.http_build_query($parameters);
      // var_dump($message); echo "</br>";
      echo (isDebugMode ? "<br>[+] URL: ".$url : "" );
  		curl_setopt_array($curl, array(
  			CURLOPT_URL => $url,
  			CURLOPT_USERAGENT => 'GATEKEEPER',
  			CURLOPT_POST => true,
  			CURLOPT_RETURNTRANSFER => true,
  			CURLOPT_VERBOSE => true
  		));
  		$result = curl_exec($curl);
  		print_r($result);
  	  curl_close($curl);
  	}
  }

  function sendSMS($number,$message,$params=array()){
        $number = $this->cleanNumber($number);
        $curl = curl_init();
        $curlopt = array(
            CURLOPT_URL => 'https://api.txt4me.com/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>
            (http_build_query(array(
                "message" => array(
                    'to' => $number,
                    'body' => $message,
                    'from' => 'INFO'
                    )
            ))),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NTc5OTk4MjksImp0aSI6ImhHTFMyRHIwNFJqNnhnOElubFBPVkE9PSIsImlzcyI6Imh0dHBzOlwvXC9hcGkudHh0NG1lLmNvbSIsInN1YiI6IkdpZC04OTg0NjMgNmY6MWY6YTE6NWU6ZjU6MTA6YTM6N2U6MDI6YjI6OWE6MDM6NmI6NDY6ZWE6YzgiLCJuYW1lIjoidHh0NG1lX2FpbmdlbGMifQ.NVPjmkDQDt0AVMYm_cmlOvechARpZ3KgUxzn73M6fVY",
                "X-Token: Secret 3e98cc8f50af5ec48e26142689838b7a3a7b55ee",
                "cache-control: no-cache"
            ),
            #CURLOPT_SSL_VERIFYHOST => false,
            #CURLOPT_SSL_VERIFYPEER => false,
        );
        curl_setopt_array($curl,$curlopt);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        return array(
            'response' => strpos($response,"{")!==false
                ? json_decode($response)
                : $response,
            'error' => $error
            );
    }

  protected function isMobileNumber($number){
  	return strlen($number)==11 || strlen($number)==12;
  }

  // Change 63 to 0.
  protected function cleanNumber($number){
    $number = $this->numberonly($number, "digits");
    $number = substr($number,0,3 == "630") ? "63".substr($number,3) : $number;
    return strlen($number) == 10 ? "63".$number : $number;

    //return strlen($number)==12 ?
  }

  private function isSetParam($param, $paramArray=array()){
    if(is_array($param)){
      return in_array($param, $paramArray, true) || array_key_exists($param, $paramArray);
    }
    return $param == $paramArray;
  }

  private function numberonly($str, $params=array()){
    if(is_array($str)){
      $keys = array_keys($str);
      $str = count($keys) <= 0 ? 0 : $str[$keys[0]];
    }

    $preg = $this->isSetParam("digits", $params) ? "/[^0-9]/" : "/[^0-9\.\-]/"; // FALSE
    $retstr = preg_replace($preg,"",$str);
    $retstr = $retstr == '' ? 0 : $retstr;
    return $this->isSetParam('string',$params) ? $retstr."" : $retstr;
  }

  private function debug($var){
    var_dump($var);
    echo "</br>";
  }

  # Set the direction, "in" or "out", based on the IP address of the arduino device making a request.
  private function setDirection($dir){
    if($dir == "192.168.8.221"){
      # IP Address for Arduino in Entrance ^
      return "in";
    }

    if($dir == "192.168.8.224"){
      # IP Address for Arduino in Exit ^
      return "out";
    }

    if($dir == "127.0.0.1"){
      # IP Address for Arduino in Exit ^
      return "out";
    }

    if($dir == "192.168.8.222"){
      # Josh PC
      return "none";
    }
  }
}



// // DO NOT USE THIS
// function populate(){
//   $mysqli = connectToDB();
//   if($mysqli){
//     $q = "SELECT idnumber FROM preschool";
//     if($query = $mysqli->query($q)){
//       if($query -> num_rows > 0){
//         while($row = $query->fetch_assoc()){
//           $insert_query = $mysqli->query("INSERT INTO kiosk_logging_test (idnumber) VALUES (".$row["idnumber"].")");
//         }
//       }
//     }
//   }
// }
