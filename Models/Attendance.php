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

  function insertToHistory($idnumber, $contact, $message, $time_sent) {
    // echo "$idnumber, $contact, $message, $time_sent<br>";
    // Check if table exists
    $q = "INSERT into proj_smshistory (idnumber, contact, message, time_sent) " .
    "VALUES (\"$idnumber\", \"$contact\", \"$message\", \"$time_sent\")";
    print_r($q); echo "<br>";
    $command = db::query(array($q));
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
      "time BETWEEN \"$y-$m-$d 00:00:00\" AND \"$y-$m-$d 23:59:59\" AND " .
      "isEval = 1 AND " .
      "idnumber IN (SELECT idnumber FROM proj_student2)";

    $results = DB::query(array($query_records_of_student));

    foreach($results as $index1 => $result) {
        foreach($result as $key => $column) {
            if($key == "time") {
                $d = new DateTime($column);
                $results[$index1][$key] = $d;
            }
        }
    }
    // echo "Records Sent: " . count($results) . "<br>";
    return $results;
  }

  function query_all_records() {
    $d = new DateTime();
    $y = $d->format("Y");
    $m = $d->format("m");
    $d = $d->format("d");

    $query_records_of_student =  "" .
      "SELECT " .
      "proj_attendance.id, " .
      "proj_student2.idnumber, " .
      "proj_student2.fname, " .
      "proj_student2.contact, " .
      "proj_attendance.gate, " .
      "proj_attendance.isSent, " .
      "proj_attendance.isEval, " .
      "proj_attendance.time " .
      "FROM proj_attendance " .
      "LEFT JOIN proj_student2 " .
      "ON proj_attendance.idnumber = proj_student2.idnumber " .
      "WHERE " .
      "proj_attendance.idnumber IN (SELECT idnumber FROM proj_student2) AND " .
      // "isEval = 0 and month(time) = 11 LIMIT 5000";
      "proj_attendance.isEval = 0 AND " .
      "proj_attendance.time BETWEEN \"$y-$m-$d 00:00:00\" AND \"$y-$m-$d 23:59:59\"";
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
      "time BETWEEN \"$y-$m-$d 00:00:00\" AND \"$y-$m-$d 23:59:59\" AND " .
      "idnumber = \"$idnumber\" AND " .
      "isSent = 1 AND " .
      "gate = \"$gate\" " .
      // "month(time) = 11 " .
      "ORDER BY id DESC LIMIT 1";
    // print_r($query_last_record_of_student); echo "<br>";
    $results = DB::query(array($query_last_record_of_student));
    return $results;
  }

  function query_record_to_send() {
    $d = new DateTime();
    $y = $d->format("Y");
    $m = $d->format("m");
    $d = $d->format("d");

    $query_record_to_send =  "" .
      "SELECT " .
      "proj_attendance.id, " .
      "proj_student2.idnumber, " .
      "proj_student2.fname, " .
      "proj_student2.contact, " .
      "proj_attendance.gate, " .
      "proj_attendance.isSent, " .
      "proj_attendance.isEval, " .
      "proj_attendance.time " .
      "FROM proj_attendance " .
      "LEFT JOIN proj_student2 " .
      "ON proj_attendance.idnumber = proj_student2.idnumber " .
      "WHERE " .
      "proj_attendance.idnumber IN (SELECT idnumber FROM proj_student2) AND " .
      "proj_attendance.isSent = 1 AND " .
      "proj_attendance.time BETWEEN \"$y-$m-$d 00:00:00\" AND \"$y-$m-$d 23:59:59\" LIMIT 180";
    $results = DB::query(array($query_record_to_send));
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
      "isSent = 2";
    $results = DB::query(array($query_record_of_student));
    return $results;
  }

  function reset() {
    $d = new DateTime();
    $y = $d->format("Y");
    $m = $d->format("m");
    $d = $d->format("d");

    $q = "" .
      "UPDATE proj_attendance ".
      "SET ".
      "isSent = 0, isEval = 0 ".
      "WHERE ".
      "time BETWEEN \"$y-$m-$d 00:00:00\" AND \"$y-$m-$d 23:59:59\"";

    $results = DB::query(array($q));
  }
}
