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

    $in_records = Core\db::query(
      array(
        "SELECT DISTINCT idnumber ".
        "FROM proj_attendance ".
        "WHERE ".
        "gate = \"in\" AND ".
        "time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\""
      ));
    if(count($in_records) > 0) {
      foreach($in_records as $idnumber) {
	$attendance_entry = Core\db::query(array("SELECT * from proj_attendance WHERE gate = \"in\" AND time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\" AND idnumber = ".$idnumber["idnumber"]));
	foreach($attendance_entry as $i => $ae) {
          // Check first entry of student if its sent, send if not.
          if($i == 0){
            if($ae["isSent"] == 0){
	      $q = "SELECT fname, contact FROM ".$studentdb." WHERE idnumber = \"".$idnumber["idnumber"]."\"";
	      $studentinfo = Core\db::query(array($q));
	      // print_r($q); echo "<br>";
              if(count($studentinfo) > 0) {
                $n = $studentinfo[0]["fname"];
                $contacts = $studentinfo[0]["contact"];
                $contacts = explode(";", $contacts);
		$msg = "$n has passed the entrance gate at ".$dateNow->format("Y-m-d H:i:sA");
                if(count($contacts) == 1) {
                  //self::sendSMS($contacts[0], $msg);
                  error_log("[IN] [SINGLE] [".$idnumber["idnumber"]."] Sending SMS to ".$contacts[0]);
                  Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($ae["id"])));
                } elseif(count($contacts) > 1) {
                  //print_r($ae);
                  foreach($contacts as $contact) {
                    //self::sendSMS($contact, $msg);
                    error_log("[IN] [MULTIPLE] [".$idnumber["idnumber"]."] Sending SMS to ".$contact);
                    Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($ae["id"])));
                  }
                }else {
                  error_log("No contact number found for ID Number ".$idnumber);
                }
              }
            }
          }
        }
      }
    }

    $out_records = Core\db::query(
      array(
        "SELECT DISTINCT idnumber ".
        "FROM proj_attendance ".
        "WHERE ".
        "gate = \"out\" AND ".
        "time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\""
      ));
    if(count($out_records) > 0) {
      foreach($out_records as $idnumber) {
	$attendance_entry = Core\db::query(array("SELECT * from proj_attendance WHERE gate = \"out\" AND time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\" AND idnumber = ".$idnumber["idnumber"]));
        foreach($attendance_entry as $i => $ae) {
          // Check first entry of student if its sent, send if not.
          if($i == 0){
            if($ae["isSent"] == 0){
              $studentinfo = Core\db::query(array("SELECT fname, contact from ".$studentdb." WHERE idnumber = ".$idnumber["idnumber"]));
              if(count($studentinfo) > 0) {
                $n = $studentinfo[0]["fname"];
                $contacts = $studentinfo[0]["contact"];
                $contacts = explode(";", $contacts);
                $msg = "$n has passed the entrance gate at ".$dateNow->format("Y-m-d H:i:sA");
                if(count($contacts) == 1) {
                  //self::sendSMS($contacts[0], $msg);
                  error_log("[FIRST] [SINGLE] [OUT] [".$idnumber["idnumber"]."] Sending SMS to ".$contacts[0]);
                  Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($ae["id"])));
                } elseif(count($contacts) > 1) {
                  foreach($contacts as $contact) {
                    //self::sendSMS($contact, $msg);
                    error_log("[FIRST] [MULTIPLE] [OUT] [".$idnumber["idnumber"]."] Sending SMS to ".$contact);
                    Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($ae["id"])));
                  }
                }else {
                  error_log("No contact number found for ID Number ".$idnumber);
                }
              }
            }
          }
        }
      }
    }

    if(count($in_records) > 0 ) {
        foreach($in_records as $idnumber) {
	    $q = "SELECT id, idnumber, time " .
		    "FROM proj_attendance " .
		    "WHERE " .
		    "idnumber = \"".$idnumber["idnumber"]."\" AND " .
		    "gate = \"in\" AND " .
		    "isSent = 1 " .
		    "ORDER BY id DESC LIMIT 1";
	    $last_in_record = Core\db::query(array($q));
	    	if(count($last_in_record) > 0) {
	   		foreach($last_in_record as $lir) {
				$next_record_query = "" .
				 "SELECT " .
				 "proj_attendance.id, " .
				 "proj_attendance.idnumber, " .
				 "proj_student2.fname, " .
				 "proj_student2.contact, " .
				 "proj_attendance.time " .
				 "FROM proj_attendance " .
				 "LEFT JOIN proj_student2 " .
				 "ON proj_attendance.idnumber = proj_student2.idnumber ".
				 "WHERE proj_attendance.gate = \"in\" AND proj_attendance.idnumber = ? and proj_attendance.id > ?";

				$nextRecords = Core\db::query(array($next_record_query, array($idnumber["idnumber"], $lir["id"])));
	   	        	$l = new DateTime($lir["time"]);
				foreach($nextRecords as $i=>$nr) {
	   	     	    		$n = new DateTime($nr["time"]);
	   	     	    		$diff = $l->diff($n);
	   	     	    		if(intval($diff->format("%h")) >= 1) {
						Core\db::query(array("UPDATE proj_attendance SET isSent = 1 WHERE id = ?", array($nr["id"])));
						$numbers = explode(";", $nr["contact"]);
						if(count($numbers) > 0) {
							foreach($numbers as $number) {
								$msg = $nr["fname"]." has passed the entrance gate at ".$n->format("Y-m-d H:i:sA");
								error_log("[SECOND] [IN] [".$nr["idnumber"]."] Sending SMS to ".$number);
								//self::sendSMS($msg, $number);
							}
						}
	   	     		    		break;
	   	     	    		}
	   	         	}
		 	}
		}
	}
    }
  
    if(count($out_records) > 0 ) {
        foreach($out_records as $idnumber) {
	    $q = "SELECT id, idnumber, time " .
		    "FROM proj_attendance " .
		    "WHERE " .
		    "idnumber = \"".$idnumber["idnumber"]."\" AND " .
		    "gate = \"out\" AND " .
		    "isSent = 1 " .
		    "ORDER BY id DESC LIMIT 1";
	    $last_out_record = Core\db::query(array($q));
	    	if(count($last_out_record) > 0) {
	   		foreach($last_out_record as $lor) {
				$next_record_query = "" .
				 "SELECT " .
				 "proj_attendance.id, " .
				 "proj_attendance.idnumber, " .
				 "proj_student2.fname, " .
				 "proj_student2.contact, " .
				 "proj_attendance.time " .
				 "FROM proj_attendance " .
				 "LEFT JOIN proj_student2 " .
				 "ON proj_attendance.idnumber = proj_student2.idnumber ".
				 "WHERE proj_attendance.gate = \"out\" AND proj_attendance.idnumber = ? and proj_attendance.id > ?";

				$nextRecords = Core\db::query(array($next_record_query, array($idnumber["idnumber"], $lor["id"])));
	   	        	$l = new DateTime($lor["time"]);
				foreach($nextRecords as $i=>$nr) {
	   	     	    		$n = new DateTime($nr["time"]);
	   	     	    		$diff = $l->diff($n);
	   	     	    		if(intval($diff->format("%h")) >= 1) {
						Core\db::query(array("UPDATE proj_attendance SET isSent = 1 WHERE id = ?", array($nr["id"])));
						$numbers = explode(";", $nr["contact"]);
						if(count($numbers) > 0) {
							foreach($numbers as $number) {
								$msg = $nr["fname"]." has passed the entrance gate at ".$n->format("Y-m-d H:i:sA");
								error_log("[SECOND] [OUT] [".$nr["idnumber"]."] Sending SMS to ".$number);
								//self::sendSMS($msg, $number);
							}
						}
	   	     		    		break;
	   	     	    		}
	   	         	}
		 	}
		}
	}
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
set_time_limit(60);
for($i = 0; $i <= 59; ++$i) {
	if($i == 0)
		error_log("CHECKING FOR SMS QUEUE");
	if($i == 30)
		error_log("CHECKING FOR SMS QUEUE");

  SMS::Sender();
  sleep(1);
}
