<?php

class modulesController extends Controller{

    function students() {
        require(ROOT . "Models/Student.php");
        $student = new Student();

        $d["students"] = $student->showAll();
        $this->set($d);
        $this->ajax("students");
    }

    function addStudent() {
        require(ROOT . "Models/Student.php");
        $student = new Student();

        // $d["students"] = $student->showAll();
        // $this->set($d);
        $this->ajax("students");
    }

}