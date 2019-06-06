<?php
$content = "<div class='page-content' style='width: 100$; height: calc(100% - 32px);'";
$content =. "<table class='table'>";
$content =. "<thead>";
  $content =. "<tr>";
    $content =. "<th>Semester</th>";
    $content =. "<th>Start</th>";
    $content =. "<th>End</th>";
  $content =. "</tr>";
$content =. "</thead>";
$content =. "<tbody>";
  $semesters = Core\db::query(array("SELECT * FROM lormasj_schoolyear", array()));
  foreach($semesters as $semester) {
    $content =. "<tr>";
      $content =. "<td>".$semester["sy"]."</td>";
      $content =. "<td>".$semester["startDate"]."</td>";
      $content =. "<td>".$semester["endDate"]."</td>";
    $content =. "</tr>";
  }
$content =. "</tbody>";
$content =. "</table>";
$content .= "</div>";

echo $content;
