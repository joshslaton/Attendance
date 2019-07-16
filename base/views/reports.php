<?php
$content = "";
$content .= "<div class='page-content' style='width: 100%; height: calc(100% - 34px); padding: 10px;'>";

$content .= "<div class='form-group reportsOptions' data-script='getListOfStudents'>";
  $content .= "<label for='gradeSelect'>Filter: </label>";
  $gradeLevels = Core\db::query(array("SELECT ylevel FROM proj_yearlevel", array()));
  $content .= "<select id='gradeSelect' class='form-control'  style='width: auto;'>";
    $content .= "<option selected='selected' value=''>Filter by Grade</option>";
    // $content .= "<option selected='selected' value='G11-HAS'>Grade 11 HAS</option>";
    foreach($gradeLevels as $g) {
      $content .= "<option value='".$g["ylevel"]."'>".$g["ylevel"]."</option>";
    }
  $content .= "</select>";
  // $content .= "</div>";
  //
  // $content .= "<div class='form-group'>";
  $content .= "<label for='viewSelect'>View: </label>";
    $content .= "<select id='viewSelect' class='form-control' style='width: auto;'>";
      // $content .= "<option selected='selected' value=''>Type of View</option>";
      $content .= "<option value=''>Type of View</option>";
      $content .= "<option value='Classcard'>Classcard</option>";
      $content .= "<option selected='selected' value='DTR'>DTR</option>";
  $content .= "</select>";
  $content .= "<input type='button' id='search' class='btn btn-primary' value='Search'>";
$content .= "</div>";

$content .= "<table id='studentTable' class='table studentTable'>";
  $content .= "<thead>";
    $content .= "<tr><th>ID Number</th><th>Name</th><th>Attendance</th></tr>";
  $content .= "</thead>";
$content .= "<tbody>";
$content .= "</tbody>";
$content .= "</table>";

$content .= "</div>";

echo $content;
?>
