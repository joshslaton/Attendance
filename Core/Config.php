<?php

class Config {

  // Constants throughout the file
  public $config = array(
    "attendanceTable" => "proj_attendance",
    "studentTable" => "proj_student2",
    "schoolYear" => "2019-2020",
  );

  function __construct__() {

  }

  function get($key) {
    if(!is_array($this->config)) {
      $this->config = array();
    }

    if(array_key_exists($key, $this->config)) {
      return $this->config[$key];
    }else {
      echo "Config not available!";
      exit;
    }
  }
}
