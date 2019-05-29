<?php
  $content = "<div class='page-content' style='width: 100%; height: calc(100% - 32px)'>";

  // TODO: add data table for searching

  $content .= "<table id='studentTable' class='table' data-script='loadStudentTable'>";
  $content .= "<thead>";
  $content .= "<tr style='text-align: left;'>";
  $content .= "<th>ID Number</th>";
  $content .= "<th>Name</th>";
  $content .= "<th>Attendance</th>";
  $content .= "</tr>";
  $content .= "</thead>";
  $content .= "<tbody>";

  if(isset($_GET["grade"]) && $_GET["grade"]) {
    $results = Core\db::query(array("SELECT idnumber, name FROM preschool WHERE grade = ?", array($_GET["grade"])));

        foreach($results as $result) {
          $content .= "<tr data-idnumber='".$result["idnumber"]."' style='text-align: left;'>";
          $content .= "<td>".$result["idnumber"]."</td>";
          $content .= "<td>".$result["name"]."</td>";
          $content .= "<td><a href='#View'>View</a> | <a href='#View'>Edit</a> | <a href='#View'>Print</a></td>";
          $content .= "</tr>";
        }
  }

  $content .= "</tbody>";
  $content .= "</table>";
  $content .= "</div>";
  echo $content;
?>
