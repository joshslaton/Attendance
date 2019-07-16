<?php
namespace Core;

DEFINE("isDebugMode", True);
DEFINE("cooldown", 10); // 10 second for every tap per student

class SMS3 {
  public static function init() {
    $_GET["idnumber"] = "1700023";
    $_GET["direction"] = "in";
    $dir = $_GET["direction"];
    $idnumber = $_GET["idnumber"];
    $currentDate = new \DateTime();

    if(!is_null($idnumber) && !is_null($dir)) {

      $studentArray = db::query(array("SELECT * FROM proj_student WHERE idnumber = ?", array($idnumber)));
      $timeRecord = db::query(array("SELECT time FROM proj_logs WHERE idnumber = ? AND dir = ? ORDER BY id DESC LIMIT 1",array($idnumber, $dir)));

      // If student Exists
      if($studentArray > 0) {
        //  If there is a time record
        if(\count($timeRecord) > 0) {
          $timeRecord = new \DateTime($timeRecord[0]["time"]);
          $interval = $currentDate->getTimestamp() - $timeRecord->getTimestamp();
          /* Check the difference in time (in seconds)
           * if its higher than the cooldown, insert an entry
           * if its less than the cooldown, do nothing
           */
          if($interval > cooldown) {
            self::recordToLogs($studentArray, $dir);
          }
        // Insert new time record if it does not exist
        } else {
          self::recordToLogs($studentArray, $dir);
        }
      }
    }
  }


  private static function recordToLogs($studentArray, $dir) {
    $m = self::buildMessage($studentArray, $dir);
    $s = $studentArray[0];
    $d = new \DateTime();
    $q = "INSERT INTO proj_logs(`idnumber`, `message`, `time`, `dir`) VALUES (?, ?, ?, ?)";
    $results = db::query(array($q, array($s["idnumber"], $m, $d->format("Y-m-d H:i:s"), $dir)));
  }

  private static function buildMessage($studentArray, $dir) {
    $g = ($dir == "in") ? "Entrance" : "Exit";
    $s = $studentArray[0];
    $d = new \DateTime();
    $n = $s["fname"]." ".$s["mname"]." ".$s["lname"];
    $m = $n." passed the ".$g." gate at ".$d->format("F-d-y h:i:sa");

    return $m;
  }
}
