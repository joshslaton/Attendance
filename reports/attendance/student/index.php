<?php
  include $_SERVER["DOCUMENT_ROOT"]."/classes/student.php";
  $s = new student();
?>
<html>
  <head>
    <script src='/includes/jquery.min.js' type='text/javascript'></script>
    <link href='/includes/bootstrap.min.css' rel='stylesheet'>
    <script src='/includes/bootstrap.min.js'></script>
    <script src='/includes/bootstrap.bundle.min.js' type='text/text/javascript'></script>
    <script src='scripts.js'></script>
    <link href='/style.css' rel='stylesheet'>
  </head>
  <body>
    <?php include_once($_SERVER["DOCUMENT_ROOT"]."/templates/header.php"); ?>
    <div class='nav-sub-container'>
      <div class='dropdown'>
      <div>
        <ul id='nav-sub'>
          <li><a id='attendance' href='#'>Attendance</a></li>
        </ul>
      </div>
      <div class='dropdown-content'>
        <a href='http://localhost/reports/attendance/student/' id='reports-category-bystudent'>By Student</a>
        <a href='http://localhost/reports/attendance/grade/' id='reports-category-bygrade'>By Grade</a>
        <a href='http://localhost/reports/attendance/section/' id='reports-category-bysection'>By Section</a>
      </div>
    </div>
    </div>
    <div class='content-wrapper'>
      <?php
        $cal = new Calendar();
        foreach($cal->monthsToIterate("2018-08-13", "2019-05-31") as $my){
          $month = explode(" ", $my)[0];
          $year = explode(" ", $my)[1];
          $calendar = $cal->build_calendar($month, $year);
          print $calendar;
        }
      ?>
    </div>
  </body>
</html>
