<?php
$content = "";

$content .= "<div class='page-content' style='width: 100%; height: calc(100% - 34px); padding: 10px;'>";

// TODO: Automate this so it does not repeat
// $content .= "<button class='accordion' style='background-color: rgb(255, 0, 0, 0.3);'>Preschool</button>";
// $content .= "<div class='panel'>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=nursery'>Nursery</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=toddler'>Toddler</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=kinder1'>Kinder 1</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=kinder2'>Kinder 2</a></p>";
// $content .= "</div>";
//
// $content .= "<button class='accordion' style='background-color: rgb(255, 255, 0, 0.3);'>Grade School</button>";
// $content .= "<div class='panel'>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade1'>Grade 1</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade2'>Grade 2</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade3'>Grade 3</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade4'>Grade 4</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade5'>Grade 5</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade6'>Grade 6</a></p>";
// $content .= "</div>";
//
// $content .= "<button class='accordion' style='background-color: rgb(0, 255, 0, 0.3);'>Junior High School</button>";
// $content .= "<div class='panel'>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade7'>Grade 7</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade8'>Grade 8</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade9'>Grade 9</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade10'>Grade 10</a></p>";
// $content .= "</div>";
//
// $content .= "<button class='accordion'style='background-color: rgb(0, 0, 255, 0.3);'>Senior High School</button>";
// $content .= "<div class='panel'>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade11'>Grade 11</a></p>";
// $content .= "<p><a href='".Core\Registry::get("config/url/base")."views/reports/section/?grade=grade12'>Grade 12</a></p>";
// $content .= "</div>";

// $content .= "</form>";

$content .= "<div class='form-group'>";
$content .= "<label for='grade'>Filter: </label>";
$content .= "<select id='gradeSelect' data-script='studentTable'>";
$content .= "<option selected='selected'>Filter by Grade</option>";
$content .= "<option>Nursery</option>";
$content .= "<option>Toddler</option>";
$content .= "<option>Kinder</option>";
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
