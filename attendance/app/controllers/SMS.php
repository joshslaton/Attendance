<?php
namespace Core;

DEFINE("isDebugMode", True);
DEFINE("cooldown", True);

class SMS3 {
  public static function init() {
    $_GET["idnumber"] = "1700023";
    $_GET["direction"] = "in";
    $dir = $_GET["direction"];
    $idnumber = $_GET["idnumber"];

    if(!is_null($idnumber) && !is_null($dir)) {
      // $exists = db::query(array("SELECT idnumber FROM proj_student WHERE idnumber = ?", array($idnumber, $message, $time, $dir)));
      $exists = db::query(array("SELECT idnumber FROM proj_student WHERE idnumber = ?", array($idnumber)));
      print_r(count($exists));
      if(\count($exists) > 0)
        self::recordToLogs($idnumber, $dir);
    }
  }
  // prevent tapping too many times.
  private static function recordToLogs($idnumber, $dir) {
    $message = "";
    $d = new \DateTime();
    print($idnumber." ".$message." ".$d->format("Y-m-d H:i:s")." ".$dir);
    if($dir == "in") {
      $q = "INSERT INTO proj_logs(`idnumber`, `message`, `time`, `dir`) VALUES (?, ?, ?, ?)";
      $results = db::query(array($q, array($idnumber, $message, $d->format("Y-m-d H:i:s"), $dir)));
    }
    if($dir == "out") {
      $q = "INSERT INTO proj_logs(`idnumber`, `message`, `time`, `dir`) VALUES (?, ?, ?, ?)";
      $results = db::query(array($q, array($idnumber, $message, $d->format("Y-m-d H:i:s"), $dir)));
    }
  }
}
