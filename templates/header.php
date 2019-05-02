<?php
// include_once $_SERVER['DOCUMENT_ROOT'].'includes/DB.inc.php';
// $db = new db("localhost", "kiosk", "kiosk", "school");
// $results = $db->query("SELECT year FROM school_year")->fetchAll();
?>

<div class='nav-main-container'>
  <div>
    <ul id='nav-main'>
      <li id='nav-home'><a href='http://localhost/'>Home</a></li>
      <li id='nav-students'><a href='http://localhost/student/'>Students</a></li>
      <li><a href='#'>Users</a></li>
      <li><a href='#'>Schedule</a></li>
      <li id='nav-reports'><a href='http://localhost/reports/'>Reports</a></li>
      <li id='nav-schoolyear'>
        <select class='form-control'>
          <option>2018-2019</option>";
          <option>2019-2020</option>";
        </select>
      </li>
    </ul>
  </div>
</div>
