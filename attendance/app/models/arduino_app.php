<?php
// include_once('/var/www/html2/attendance/preschool/modules/SMS/config.php');
// include_once("/var/www/html2/attendance/preschool/modules/SMS/sms_in.php");

namespace Core;
class App {
  // TODO: logging needs to go here
  // TODO: organize functions inside the class

  function init(){
    // $sms = new SMS();
    $queue = array();
    // $db = new DB(DBHOST, DBUSER, DBPASS, DBNAME);
    $q = "SELECT gatekeeper_in.id, gatekeeper_in.idnumber, preschool.name, gatekeeper_in.message, gatekeeper_in.time_recorded ,preschool.contact1, preschool.contact2, preschool.contact3 ";
    $q .= "FROM gatekeeper_in ";
    $q .= "LEFT JOIN preschool ";
    $q .= "ON preschool.idnumber = gatekeeper_in.idnumber ";
    $q .= "WHERE isSent = 0";
    $results = db::query(array($q, array()));
    foreach($results as $notSent){
      array_push($queue, $notSent);
    }
    if(!empty($results)){
      // var_dump($results);
      foreach($results as $result){
        foreach($result as $k =>$v){
          if(preg_match("/contact[0-9]/", $k, $match)){ // check if theres are "contact" rows
            foreach($match as $contact){ // get the name of the table based on regex results above
              if(SMS::isMobileNumber(SMS::cleanNumber($result[$contact]))){
                 $number = SMS::cleanNumber($result[$contact]);
                 $msg = $result["message"];
                 // $sms->sendSMS("admin",$number,$msg);
                // SMS::sendSMS($number,$msg);
                 //printf("%s - Sending to: %s - | Message: %s.</br>", $result["id"],$number, $msg);
                 // exec("echo  [".date("F-d-Y H:i:s")."] - CRON - ".WEBROOT."/index.php"." - Sending to: ".$number." ".$msg." >> logs");
                 $update = db::query("UPDATE gatekeeper_in SET isSent = 1 WHERE id = ".$result["id"]);
              }
            }
          }
        }
      }
    }else{
      exec("echo  [".date("F-d-Y H:i:s")."] - CRON - ".WEBROOT."/index.php - There are no messages to be pushed >> ./logs");
    }

  } // End of init class


} // End of App Class

$app = new App();
$app->init();
?>
