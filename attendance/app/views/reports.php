<?php
$content = "";

$content .= "<div class='page-content' style='width: 100%; height: calc(100% - 34px); padding: 10px;'>";

$content .= "<div class='form-group' data-script='getListOfStudents'>";
$content .= "<label for='gradeSelect'>Filter: </label>";
$gradeLevels = Core\db::query(array("SELECT ccode, cname FROM proj_course", array()));
$content .= "<select id='gradeSelect' class='form-control'  style='width: auto;'>";
$content .= "<option selected='selected' value=''>Filter by Grade</option>";
foreach($gradeLevels as $g) {
  $content .= "<option value='".$g["ccode"]."'>".$g["cname"]."</option>";
}
$content .= "</select>";
// $content .= "</div>";
//
// $content .= "<div class='form-group'>";
$content .= "<label for='viewSelect'>View: </label>";
$content .= "<select id='viewSelect' class='form-control' style='width: auto;'>";
// Default View ?
$content .= "<option selected='selected' value=''>Type of View</option>";
$content .= "<option value='DTR'>DTR</option>";
$content .= "<option value='Classcard'>Classcard</option>";
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
