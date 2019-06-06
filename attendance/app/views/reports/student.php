<?php
function createTable($monthName) {
  $t = "<table class='table' style='width: auto;'>";
  $t .= "<thead><tr><th>".$monthName."</th></tr></thead>";
  $t .= "</table>";

  return $t;
}


$content = "<div class='page-content' style='width: 100%;'>";

if(isset($_SESSION["schoolYear"]) && $_SESSION["schoolYear"] != "") {
  $q = "SELECT timestamp FROM proj_attendance WHERE idnumber = ? and syear = ?";
  $results = Core\db::query(array($q,array($_GET["idnumber"], $_SESSION["schoolYear"])));

  $months = [];

  foreach($results as $result) {
    $m = explode("-",explode(" ", $result["timestamp"])[0])[1];
    if(!in_array($m, $months))
      array_push($months, $m);
  }

  foreach($months as $month) {
    $monthName = date("F", mktime(0, 0, 0, $month, 1));
    $content .= createTable($monthName);
  }
}

$content .= '</div>';
echo $content;


?>
