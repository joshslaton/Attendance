<?php

class attendanceController extends Controller{

  function search() {
    session_start();
    if($this->is_logged_in()) {
      include_once("../Models/Student.php");
      $student = new Student();
      $d["studentList"] = $student->getStudentList();
      $this->set($d);
      $this->render("search");
    }else {
      header("Location: /");
      exit();
    }
  }

  function view() {
    if(isset($_POST["idnumber"])) {
      include_once("../Models/Attendance.php");
      include_once("../Models/Student.php");
      $student = new Student();
      $attendance = new Attendance();
      $stylesheet = file_get_contents(dirname(__FILE__, 2) . "/Plugins/timecard.css");
      $d["stylesheet"] = $stylesheet;
      $d["studentInfo"] = $student->studentGetInfo($_POST["idnumber"]);
      $d["attendanceList"] = $attendance->query_record_of_student($_POST["idnumber"], $_POST["optionMonth"]);
      $this->set($d);
    }
    $this->renderNoMenu("view");
  }

  function reset() {
    include_once("../Models/Attendance.php");
    $attendance = new Attendance();
    $attendance->reset();

  }

  function grade() {
    session_start();
    if($this->is_logged_in()) {
      include_once("../Models/Student.php");
      include_once("../Models/Attendance.php");
      $s = new Student();
      $a = new Attendance();
      if(isset($_POST["gradeLevel"]) &&
      isset($_POST["month"]) &&
      isset($_POST["year"])) {
        $records = array();
        $month = $_POST["month"];
        $year = $_POST["year"];
        $daysInMonth = (new DateTime("$year-$month-1"))->format("t");
        $students = $s->studentPerGradeLevel($_POST["gradeLevel"]);
        foreach($students as $student) {
          for($day = 1; $day <= $daysInMonth; $day++) {
            $results = $a->attendance_per_day_of_month($student["idnumber"], $month, $year, $day);
            if($results) {
              $records[strval($student["idnumber"])][strval($day)] = $results[0]["count"];
            }
          }
        }
        $d["students"] = $students;
        $d["records"] = $records;
        $d["gradeLevel"] = $_POST["gradeLevel"];
        $d["month"] = $_POST["month"];
        $d["year"] = $_POST["year"];
        $d["grade"] = $s->yearLevels();
        $this->set($d);
        $this->renderNoMenu("grade");
      }else {
        $d["grade"] = $s->yearLevels();
        $this->set($d);
        $this->render("grade");
      }

    }else {
      header("Location: /user/login/");
    }
  }
}
