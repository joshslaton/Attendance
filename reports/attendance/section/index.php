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

    <div class='sidebar'>
      <p class='options-title'>Gender</p>
      <?php
        // Male
        echo "<div class='form-group options-input'>";
        echo "<input name='gender' name='gender' id='options-gender-m' class='sidebar-options' type='checkbox' value='m'>";
        echo "<label name='gender' class='form-check-label' for='options-gender-m'>Male</label>";
        echo "</div>";
        // Female
        echo "<div class='form-group options-input'>";
        echo "<input name='gender' id='options-gender-f' class='sidebar-options' type='checkbox' value='f'>";
        echo "<label class='form-check-label' for='options-gender-f'>Female</label>";
        echo "</div>";
      ?>

      <?php
        // put this in a function
        foreach($s->getGradeLevel() as $gl){
          echo "<p class='options-title'>".ucfirst($gl)."</p>";
          $sections = $s->getSections($gl);
          foreach($sections as $section){
            echo "<div class='form-group options-input'>";
            echo "<input name='section' id='section-".$section."' class='sidebar-options' type='radio' value='".$section."'>";
            echo "<label class='form-check-label' for='section-".$section."'>".ucfirst($section)."</label>";
            echo "</div>";
          }
        }
      ?>
    </div>

    <div class='content-wrapper'>
      <?php
        include "functions.php";
      ?>
    </div>
  </body>
</html>
