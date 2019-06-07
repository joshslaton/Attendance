<?php

namespace Core;

class Attendance {

  public static function getPeriod() {
    $schoolYear = self::setSchoolYear();
    $schoolYear = $schoolYear[0];
    $p = db::query(array("SELECT * FROM proj_period WHERE year = ?", array($schoolYear["year"])));
    return $p;
  }

  public static function schoolYear($requestType) {
    if($requestType == "get") {
      $sy = db::query(array("SELECT * FROM proj_sy", array()));

      return $sy;
    }

    if($requestType == "set") {
      $sy = db::query(array("SELECT * FROM proj_sy WHERE isActive = 1", array()));
      // print_r($sy);
      return $sy[0];
    }
  }

  public static function requestAttendance($idnumber) {
    $q = "SELECT gate, time, remarks FROM proj_attendance WHERE idnumber = ?";
    $results = db::query(array($q, array($idnumber)));
    return $results;
  }


}
