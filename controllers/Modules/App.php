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
        $startDate = "2019-09-12";
        $endDate = "2019-09-12";
        // Take only the records for today, IDs that have length between 3 and 7
        
        $in_records = Core\db::query(
            array(
                "SELECT DISTINCT idnumber ".
                "FROM proj_attendance ".
                "WHERE ".
                "gate = \"in\" AND ".
                "time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\" AND " .
                "LENGTH(idnumber) >= 3 AND LENGTH(idnumber) <= 7"
            ));

        if(count($in_records) > 0) {
            $prevRecord = "";
            foreach($in_records as $idnumber) {
                $attendance_entry = Core\db::query(
                    array(
                        "SELECT " . 
                        "proj_attendance.id, " .
                        "proj_attendance.idnumber, " .
                        "proj_student2.fname, " .
                        "proj_student2.lname, " . 
                        "proj_student2.contact, " .
                        "proj_attendance.time, " .
                        "proj_attendance.isSent " .
                        "FROM " .
                        "proj_attendance " .
                        "LEFT JOIN proj_student2 " .
                        "ON proj_attendance.idnumber = proj_student2.idnumber " .
                        "WHERE gate = \"in\" AND " .
                        "proj_attendance.idnumber = \"" . $idnumber["idnumber"] ."\" AND " .
                        "time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\""
                    )
                );
                foreach($attendance_entry as $i => $ae) {
                    // Send the first entry found if not.
                    // Only send IDs that have complete names and contact
                    $toCheck = array(
                        $ae["fname"],
                        $ae["contact"]
                    );

                    if(self::isValid($toCheck)) {
                        print_r($ae); echo "<br>";
                        if($i == 0) {
                            // print_r($ae); echo "<br>";
                            if($ae["isSent"] == 0) {
                                $prevRecord = $ae;
                                // print_r($prevRecord); echo "<br>";
                                $n = $ae["fname"];
                                $contacts = $ae["contact"];
                                $contacts = explode(";", $contacts);
                                $msg = "$n has passed the entrance gate at ".$ae["time"];
                                foreach($contacts as $contact) {
                                    // self::sendSMS($contact, $msg);
                                    error_log("[IN] [".$idnumber["idnumber"]."] Sending SMS to ".$contact);
                                    Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($ae["id"])));
                                }
                            }
                        }else if($i > 0) {
                            if($prevRecord != "") {
                                if($prevRecord["idnumber"] == $ae["idnumber"]) {
                                    print_r($prevRecord); echo "<br>";
                                    $prevTimeRecord = new DateTime($prevRecord["time"]);
                                    $nextTimeRecord = new DateTime($ae["time"]);
                                    $diffTimeRecord = $nextTimeRecord->diff($prevTimeRecord);
                                    if(intval($diffTimeRecord->format("%h")) >= 1) {
                                        $prevRecord = $ae;
                                        print_r($ae); echo "<br>";
                                        $n = $ae["fname"];
                                        $contacts = $ae["contact"];
                                        $contacts = explode(";", $contacts);
                                        $msg = "$n has passed the entrance gate at ".$ae["time"];
                                        foreach($contacts as $contact) {
                                            //print_r($ae); echo "<br>";
                                            // self::sendSMS($contact, $msg);
                                            error_log("[IN] [".$idnumber["idnumber"]."] Sending SMS to ".$contact);
                                            Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($ae["id"])));
                                        }
                                    }
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
                "time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\" AND " .
                "LENGTH(idnumber) >= 3 AND LENGTH(idnumber) <= 7"
            ));

        if(count($out_records) > 0) {
            $prevRecord = "";
            foreach($out_records as $idnumber) {
                $attendance_entry = Core\db::query(
                    array(
                        "SELECT " . 
                        "proj_attendance.id, " .
                        "proj_attendance.idnumber, " .
                        "proj_student2.fname, " .
                        "proj_student2.lname, " . 
                        "proj_student2.contact, " .
                        "proj_attendance.time, " .
                        "proj_attendance.isSent " .
                        "FROM " .
                        "proj_attendance " .
                        "LEFT JOIN proj_student2 " .
                        "ON proj_attendance.idnumber = proj_student2.idnumber " .
                        "WHERE gate = \"out\" AND " .
                        "proj_attendance.idnumber = \"" . $idnumber["idnumber"] ."\" AND " .
                        "time BETWEEN \"$startDate 00:00:00\" AND \"$endDate 23:59:59\""
                    )
                );
                foreach($attendance_entry as $i => $ae) {
                    // Send the first entry found if not.
                    // Only send IDs that have complete names and contact
                    $toCheck = array(
                        $ae["fname"],
                        $ae["contact"]
                    );

                    if(self::isValid($toCheck)) {
                        print_r($ae); echo "<br>";
                        if($i == 0) {
                            // print_r($ae); echo "<br>";
                            if($ae["isSent"] == 0) {
                                $prevRecord = $ae;
                                // print_r($prevRecord); echo "<br>";
                                $n = $ae["fname"];
                                $contacts = $ae["contact"];
                                $contacts = explode(";", $contacts);
                                $msg = "$n has passed the entrance gate at ".$ae["time"];
                                foreach($contacts as $contact) {
                                    // self::sendSMS($contact, $msg);
                                    error_log("[OUT] [".$idnumber["idnumber"]."] Sending SMS to ".$contact);
                                    Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($ae["id"])));
                                }
                            }
                        }else if($i > 0) {
                            if($prevRecord != "") {
                                if($prevRecord["idnumber"] == $ae["idnumber"]) {
                                    print_r($prevRecord); echo "<br>";
                                    $prevTimeRecord = new DateTime($prevRecord["time"]);
                                    $nextTimeRecord = new DateTime($ae["time"]);
                                    $diffTimeRecord = $nextTimeRecord->diff($prevTimeRecord);
                                    if(intval($diffTimeRecord->format("%h")) >= 1) {
                                        $prevRecord = $ae;
                                        print_r($ae); echo "<br>";
                                        $n = $ae["fname"];
                                        $contacts = $ae["contact"];
                                        $contacts = explode(";", $contacts);
                                        $msg = "$n has passed the entrance gate at ".$ae["time"];
                                        foreach($contacts as $contact) {
                                            //print_r($ae); echo "<br>";
                                            // self::sendSMS($contact, $msg);
                                            error_log("[OUT] [".$idnumber["idnumber"]."] Sending SMS to ".$contact);
                                            Core\db::query(array("UPDATE proj_attendance SET isSent=\"1\" WHERE `id` = ?", array($ae["id"])));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }



    // TODO: Send maximum of 200 messages in a minute
    public static function sendSMS($number,$message,$params=array()){
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
//set_time_limit(60);
//for($i = 0; $i <= 59; ++$i) {
    SMS::Sender();
//    sleep(1);
//}
