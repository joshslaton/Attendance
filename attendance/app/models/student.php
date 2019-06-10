<?php
namespace Core;

class Student {

  public static function getStudentList($grade) {
    $list = db::query(array("SELECT * FROM proj_student WHERE ccode = ?", array($grade)));
    return $list;
  }

  
}
