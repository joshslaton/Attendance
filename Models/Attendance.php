<?php

class Attendance {

  function insert($idnumber, $gate, $time, $schoolYear) {
    $command = db::query(
      array(
        "INSERT into proj_attendance (idnumber, gate, time, syear) " .
        "VALUES (?, ?, ?, ?)",
        array(
          $idnumber,
          $gate,
          $time,
          $schoolYear
        )
      )
    );
    // Do i need to log this ?
  }

  function update_record($id, $params = array()) {
    $q = "UPDATE proj_attendance SET ";
    foreach($params as $i => $v) {
      $q .= "$v, ";

      if($i == count($params)-1)
        $q .= "$v";

    }
    $q .= " WHERE id = $id";
    DB::query(array($q));
  }



  function query_all_records_for_display() {
    $d = new DateTime();
    $y = $d->format("Y");
    $m = $d->format("m");
    $d = $d->format("d");

    $query_records_of_student =  "" .
      "SELECT * FROM proj_attendance " .
      "WHERE " .
      // "time BETWEEN \"$y-$m-$d 00:00:00\" AND \"$y-$m-$d 23:59:59\" AND " .
      "month(time) = 11 AND " .
      "idnumber IN (SELECT idnumber FROM proj_student2)";

    $results = DB::query(array($query_records_of_student));
    return $results;
  }

  function query_all_records() {
    $d = new DateTime();
    $y = $d->format("Y");
    $m = $d->format("m");
    $d = $d->format("d");

    $query_records_of_student =  "" .
      "SELECT * FROM proj_attendance " .
      "WHERE " .
      "idnumber IN (SELECT idnumber FROM proj_student2) AND " .
      "isEval = 0 and month(time) = 11 LIMIT 5000";

    $results = DB::query(array($query_records_of_student));
    return $results;
  }
  // function query_all_records() {
  //   $d = new DateTime();
  //   $y = $d->format("Y");
  //   $m = $d->format("m");
  //   $d = $d->format("d");
  //
  //   $query_records_of_student =  "" .
  //     "SELECT * FROM proj_attendance " .
  //     "WHERE " .
  //     "time BETWEEN \"$y-$m-$d 00:00:00\" AND \"$y-$m-$d 23:59:59\" AND " .
  //     "idnumber IN (SELECT idnumber FROM proj_student2) AND " .
  //     "isEval = 0";
  //
  //   $results = DB::query(array($query_records_of_student));
  //   return $results;
  // }

  function query_last_record_of_student($idnumber, $gate) {
    $d = new DateTime();
    $y = $d->format("Y");
    $m = $d->format("m");
    $d = $d->format("d");

    $query_last_record_of_student =  "" .
      "SELECT * FROM proj_attendance " .
      "WHERE " .
      // "time BETWEEN \"$y-$m-$d 00:00:00\" AND \"$y-$m-$d 23:59:59\" AND " .
      "idnumber = $idnumber AND " .
      "isSent = 1 AND " .
      "gate = ? AND " .
      "month(time) = 11 " .
      "ORDER BY id DESC LIMIT 1";

    $results = DB::query(array($query_last_record_of_student, array($gate)));
    return $results;
  }

  function query_record_of_student($idnumber, $month) {
    $d = new DateTime();
    $y = $d->format("Y");
    $m = $d->format("m");
    $d = $d->format("d");

    $query_record_of_student =  "" .
      "SELECT * FROM proj_attendance " .
      "WHERE " .
      "MONTH(time) = $month AND " .
      "idnumber = $idnumber AND " .
      "isSent = 1";
    $results = DB::query(array($query_record_of_student));
    return $results;
  }
}
