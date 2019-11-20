<?php

class attendanceController extends Controller{
  private $view = "";
  function index() {
    $this->render("index");
  }

  function search() {
    include_once("../Models/Student.php");
    $student = new Student();
    $d["studentList"] = $student->getStudentList();
    $this->set($d);
    $this->render("search");
  }

  function view() {
    if(isset($_POST["idnumber"])) {
      include_once("../Models/Attendance.php");
      include_once("../Models/Student.php");
      $student = new Student();
      $attendance = new Attendance();
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
}
