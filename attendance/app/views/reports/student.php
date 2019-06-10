<?php
$content = "<div class='page-content' style='width: 100%;' data-script='showAttendance'>";


$content .= "<div class='form-group'>";
$content .= "Filter by Month and Year: ";
$content .= "<div class='form-group' style='display: inline-block; margin: 5px;'>";
  $content .= "<select class='form-control' style='width: auto;'>";
  $content .= "<option selected>Month</option>";
    for($m = 1; $m <= 12; $m ++) {
      $content .= "<option>".date("F", mktime(0, 0, 0, $m, 1))."</option>";
    }
  $content .= "</select>";
$content .= "</div>";

$content .= "<div class='form-group' style='display: inline-block; margin: 5px;'>";
  $content .= "<select class='form-control' style='width: auto;'>";
  $content .= "<option selected>Year</option>";
      $schoolYear = Core\db::query(array("SELECT name, syear FROM proj_sy WHERE isActive = 1"));
      foreach($schoolYear as $sy) {
        $content .= "<option value='".$sy["syear"]."'>".$sy["syear"]."</option>";
      }
  $content .= "</select>";
$content .= "</div>";
// $content .= "<input class='btn btn-primary' type='submit' value='Submit'>";
$content .= "</div>";

if(isset($_GET["idnumber"]) && $_GET["idnumber"] != "") {
  $id = $_GET["idnumber"];
  $sy = $_SESSION["schoolYear"];

}

$content .= '</div>';
echo $content;


?>
