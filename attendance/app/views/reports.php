<?php
$content = "";

$content .= "<div class='page-content' style='width: 100%; height: calc(100% - 34px); padding: 10px;'>";

$content .= "<div class='form-group'>";
$content .= "<label for='grade'>Filter: </label>";

$gradeLevels = Core\db::query(array("SELECT ccode, cname FROM proj_course", array()));
$content .= "<select id='gradeSelect' data-script='studentTable'>";
$content .= "<option selected='selected'>Filter by Grade</option>";
foreach($gradeLevels as $g) {
  $content .= "<option value='".$g["ccode"]."'>".$g["cname"]."</option>";
}
$content .= "</select>";

$content .= "</div>";
$content .= "<table class='table studentTable'>";
$content .= "<thead>";
$content .= "<tr><th>ID Number</th><th>Name</th><th>Attendance</th></tr>";
$content .= "</thead>";
$content .= "<tbody>";
$content .= "</tbody>";
$content .= "</table>";

$content .= "</div>";

echo $content;
?>
