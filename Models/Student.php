<?php

class Student {

    function studentGetInfo($idnumber) {
      $studentInfo = DB::query(array("SELECT * FROM proj_student2 WHERE idnumber = $idnumber"));
      if(count($studentInfo) > 0) {
        return $studentInfo[0];
      }
      return array();
    }

    function getStudentList() {
      $studentList = DB::query(array("SELECT idnumber FROM proj_student2"));
      if(count($studentList) > 0) {
        return $studentList;
      }
      return array();
    }

    function studentPerGradeLevel($gradeLevel) {
      $studentList = DB::query(array("SELECT * FROM proj_student2 where ylevel=\"$gradeLevel\" ORDER BY lname"));
      if(count($studentList) > 0) {
        return $studentList;
      }
      return array();
    }

    function showAll() {
        $studentListSorted = array();
        $ylevels = DB::query(array("SELECT ylevel FROM proj_yearlevel2 ORDER BY id"));
        foreach($ylevels as $yl) {
          $yl = $yl["ylevel"];
          $q = "SELECT * FROM proj_student2 WHERE ylevel = \"$yl\"";
          $studentsPerYearLevel = DB::query(array($q));
          foreach($studentsPerYearLevel as $s) {
            array_push($studentListSorted, $s);
          }
        }
        if(count($studentListSorted) > 0) {
            return $studentListSorted;
        }
        return array();
    }

    function yearLevels() {
        $yearLevels = DB::query(array("SELECT * FROM proj_yearlevel2"));
        if(count($yearLevels) > 0) {
            return $yearLevels;
        }
        return array();
    }

    // TODO: Start up check if required tables are there. Pre-installation checks
    function create($idnumber, $fname, $mname, $lname, $contact, $ylevel) {
        $status = "";
        $message = "";
        $results = DB::query(array("SELECT idnumber FROM proj_student2 WHERE idnumber = ?", array($idnumber)));
        if(count($results) > 0){
            $status = "Failed";
            $message = "ID Number $idnumber already exists!";
        }else {
            $status = "Success";
            $message = "Adding student record success!";
            $command = DB::query(
                array(
                    "INSERT INTO " .
                    "proj_student2 (idnumber, fname, mname, lname, contact, ylevel) " .
                    "VALUES (?, ?, ?, ?, ?, ?)",
                    array($idnumber, $fname, $mname, $lname, $contact, $ylevel)
                    )
            );
        }
        $log = array(
            "status" => $status,
            "message" => $message
        );
        return $log;
    }

    function search($idnumber) {
        $q = "SELECT * FROM proj_student2 WHERE " .
            "idnumber LIKE \"%$idnumber%\" OR " .
            "fname LIKE \"%$idnumber%\" OR " .
            "mname LIKE \"%$idnumber%\" OR " .
            "lname LIKE \"%$idnumber%\"";
        $results = DB::query(array($q));
        if(count($results) > 0){
            return $results;
        }else {
            return array(""=>"");
        }
    }

    function exists($column, $val) {
      $q = "SELECT * FROM proj_student2 WHERE " .
          "$column = $val";
      $results = DB::query(array($q));
      if(count($results) > 0){
          return $results;
      }else {
          return array();
      }
    }

    function update($id, $oldVal, $newVal, $column) {
        // Check if id exists.
        if($this->exists("id", $id)) {
          if($column == "idnumber") { // ID Number
            if($this->exists("idnumber", $newVal)) {
              return array("status" => "failed" ,"message" => "Failed: ID number $newVal exists!");
            }else {
              DB::query(array("UPDATE proj_student2 SET idnumber = \"$newVal\" WHERE id = ?", array($id)));
              return array("status" => "success", "message" => "Changed $oldVal to $newVal success!");
            }
          }else {
            if($_POST["column"] == "fname") {
              DB::query(array("UPDATE proj_student2 SET fname = \"$newVal\" WHERE id = ?", array($id)));
              return array("status" => "success", "message" => "Changed $oldVal to $newVal success!");
            }
            if($_POST["column"] == "mname") {
              DB::query(array("UPDATE proj_student2 SET mname = \"$newVal\" WHERE id = ?", array($id)));
              return array("status" => "success", "message" => "Changed $oldVal to $newVal success!");
            }
            if($_POST["column"] == "lname") {
              DB::query(array("UPDATE proj_student2 SET lname = \"$newVal\" WHERE id = ?", array($id)));
              return array("status" => "success", "message" => "Changed $oldVal to $newVal success!");
            }
            if($_POST["column"] == "ylevel") {
              DB::query(array("UPDATE proj_student2 SET ylevel = \"$newVal\" WHERE id = ?", array($id)));
              return array("status" => "success", "message" => "Changed $oldVal to $newVal success!");
            }
          }
        }
    }
}
