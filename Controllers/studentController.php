<?php

class studentController extends Controller{

  // Contact Number Checking
  function index() {
    $this->render("index");
  }

  function list() {
    require(ROOT . "Models/Student.php");
    $student = new Student();

    $d["users"] = $student->showAll();
    $this->set($d);
    $this->render("ListUsers");
  }

  function create() {
    require(ROOT . "Models/Student.php");
    $student = new Student();

    if(
      isset($_POST["idnumber"]) && isset($_POST["fname"]) &&
      isset($_POST["mname"]) && isset($_POST["lname"]) &&
      isset($_POST["contact"]) && isset($_POST["ylevel"])
    ) {
      $toValidate = array(
        $_POST["idnumber"], $_POST["fname"], $_POST["mname"],
        $_POST["lname"], $_POST["contact"], $_POST["ylevel"],
      );
      if($this->validateArray($toValidate)) {
        if($command = $student->create($_POST["idnumber"], $_POST["fname"], $_POST["mname"], $_POST["lname"], $_POST["contact"], $_POST["ylevel"])) {
          // header("Location: " . WEBROOT . "student/create");
          $d["response"] = $command;
        }
      }else {
        $status = "Failed";
        $message = "One of the fields are Empty!";
        $log = array(
          "status" => $status,
          "message" => $message
        );
        $d["response"] = $log;
      }
    }
    $d["yearLevels"] = $student->yearLevels();
    $this->set($d);
    $this->render("create");
  }

  function modify() {

    if(isset($_POST["idnumber"])) {
      $toValidate=array(
        $_POST["idnumber"]
      );
      if($this->validateArray($toValidate)) {
        require(ROOT . "Models/Student.php");
        $student = new Student();
        $d["student"] = $student->search($_POST["idnumber"]);
        $d["yearLevels"] = $student->yearLevels();
        $this->set($d);
        // $this->renderNoHeaders("search");
      }
    }

    $this->render("modify");
  }

  function search() {
    if(isset($_POST["idnumber"])) {
      if($_POST["idnumber"] != "" && !is_null($_POST["idnumber"])) {
        require(ROOT . "Models/Student.php");
        $student = new Student();
        $results = $student->search($_POST["idnumber"]);
        echo json_encode($results);
      }
    }
  }

  function update() {
    require(ROOT . "Models/Student.php");
    $student = new Student();
    $results = $student->update($_POST["id"], $_POST["oldVal"], $_POST["newVal"], $_POST["column"]);
    echo json_encode(["result" => $results]);
  }

  private function validateArray($var=array()) {
    if(is_array($var)) {
      foreach($var as $v) {
        if($v != "" && !is_null($v)) {
          continue;
        }
        return false;
      }
      return true;
    }
  }
}
