<?php
namespace Core;

class Reports {

  function __construct() {}

  static function getReportBySection($section) {
    $content = '';

    $students = db::query(array('SELECT preschool.idnumber, gatekeeper_in.time_recorded FROM preschool LEFT JOIN gatekeeper_in ON preschool.idnumber = gatekeeper_in.idnumber WHERE section = ? ORDER BY gatekeeper_in.time_recorded', array($section)));

    foreach($students as $student){
      $content .= '<table>';
      $content .= '<tr>';
      $content .= '<td>'.$student.'</td>';
      $content .= '</tr>';
      $content .= '</table>';
    }

    return $content;
  }

  static function getStudentsBySection($section) {
    $content = '';

    $students = db::query(array('SELECT idnumber, name FROM preschool WHERE section = ?', array($section)));

    $content .= '<table class="table">';
    $content .= '<tr data-studentid="'.$student["idnumber"].'">';
    $content .= '<td>ID Number</td>';
    $content .= '<td>Name of Student</td>';
    $content .= '<td>Actions</td>';
    $content .= '</tr>';
    foreach($students as $student){
      $content .= '<tr data-studentid="'.$student["idnumber"].'">';
      $content .= '<td>'.$student["idnumber"].'</td>';
      $content .= '<td>'.$student["name"].'</td>';
      $content .= '<td>';
      $content .= '<a href="#View">Attendance</a>';
      $content .= '</td>';
      $content .= '</tr>';
    }
    $content .= '</table>';

    return $content;
  }
  
  static function getAttendance($idnumber) {
    // returns array of information
    $startDate = "2018-08-13";
    $endDate = "2019-05-17";
  }
} // End of class

if (isset($_GET['reportType'])) {
  if ($_GET['reportType'] == 'bySection') {
    if(isset($_GET['section']) && $_GET['section'] != '') {
      echo Reports::getStudentsBySection($_GET['section']);
    }
  }
}

?>
