<?php
namespace Core;

class Term {
  function __construct(){
  }

  function __destruct(){
  }

  function set(){
    // Setting default term and schoolyear if not set
    if(empty($_SESSION["schoolYear"]) || empty($_SESSION["term"])){
      $term = array(
        "1st Semester" => "August-December",
        "2nd Semester" => "January-May",
        "Summer" => "June-July"
      );

      foreach($term as $k => $v) {
        $startMonth = date("n", strtotime(explode("-", $v)[0]));
        $endMonth = date("n", strtotime(explode("-", $v)[1]));
        $numOfDays = date("t", strtotime($endMonth."-1-".date("Y")));
        $year = idate("Y");
        $start = mktime(0, 0, 0, $startMonth, 1, date("Y"));
        $end = mktime(0, 0, 0, $endMonth, $numOfDays, date("Y"));

        $currentDate = strtotime(date("Y-m-d"));
        if($currentDate >= $start && $currentDate <= $end){
          $term = $k." ".strval($year)."-".strval($year+1);
          $schoolYear = date("Y");
          $_SESSION["term"] = $term;
          $_SESSION["schoolYear"] = $schoolYear;
        }
      }
    } else {
    }
  }

  function get(){

  }

}
