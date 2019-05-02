<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DB.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/classes/calendar.php';
class student{

  function __construct(){
    $this->db = new db();
    $this->cal = new calendar();
  }

  // Getting grade levels based on entry of student
  function getGradeLevel(){
    $gl = Array();
    if($results = $this->db->query('SELECT DISTINCT grade from preschool')->fetchAll()){
      foreach($results as $result){
        foreach($result as $grade){
          if($grade != '')
            array_push($gl, $grade);
        }
      }
    }
    return $gl;
  }

  function getSections($gradelevel){
    $s = Array();
    if($results = $this->db->query("SELECT DISTINCT section from preschool WHERE grade='".$gradelevel."'")->fetchAll()){
      foreach($results as $result){
        foreach($result as $section){
          if($section != '')
            array_push($s, $section);
        }
      }
    }
    return $s;
  }

  function getRosterPerSection($section){
    $studentArray = Array();
    $results = $this->db->query("SELECT CONCAT(idnumber,' ',name) as student FROM preschool WHERE section ='".$section."'")->fetchAll();
    foreach($results as $result){
      array_push($studentArray, $result['student']);
    }
    return $studentArray;
  }

  function isEnrolled($studentID){
    // Just to see if student id exists in the database for now
    $q = "SELECT idnumber FROM preschool WHERE idnumber='".$studentID."'";
    $results = $this->db->query($q)->fetchAll();
    if (count($results) > 0)
      return True;
    else
      return False;

  }

  function debug($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
  }

  function showRoster($studentArray){
    $content = "<table border='1' class='table'>";
    $content .= "<thead>";
    $content .= "<td>Student ID</td>";
    $content .= "<td>Name</td>";
    $content .= "<td>Present</td>";
    $content .= "<td>Absent</td>";
    $content .= "<td></td>";
    $content .= "</thead>";
    $content .= "<tbody>";
    foreach($studentArray as $student){
      $idnumber = explode(" ", $student, 2)[0];
      $name = explode(" ", $student, 2)[1];
      $attendanceInfoArray = $this->cal->numberOfDaysPresent($idnumber);
      $content .= "<tr>";
      $content .= "<td>".$idnumber."</td><td>".$name."</td>";
      $content .= "<td>".$attendanceInfoArray[$idnumber]["present"]."</td>"; // Present
      $content .= "<td>".$attendanceInfoArray[$idnumber]["absent"]."</td>"; // Present
      $content .= "<td><input type='submit' class='btn btn-primary' value='Check Attendance'></td>";
      $content .= "</tr>";
    }
    $content .= "</tbody>";
    $content .= "</table>";
    return $content;
  }

}
$s = new student();
// print_r($s->cal->numberOfDaysPresent("2900876"));
// print_r($s->cal->numberOfDaysAbsent("2900876"));
?>
