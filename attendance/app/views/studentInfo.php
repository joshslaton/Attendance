<?php
// data returned to be process by javascript

if(($_POST["requestType"]) == "requestInfox") {
  if(isset($_POST["idnumber"]) && ($_POST["idnumber"]) != "") {
    $id = $_POST["idnumber"];
    $results = Core\db::query(array("SELECT idnumber, name FROM preschool WHERE idnumber = ?", array($id)));
    $results = $results[0];

    $content = "";
    Core\Reports::getAttendance($id);
    $content .= "<p style='font-size: 1.5em;'>".$results["idnumber"]." - ".$results["name"]."</p>";
    $content .= "<table class='table table-bordered'>";
      $content .= "<thead>";
        $content .= "<tr>";
          $content .= "<th colspan=14>School Year 2018 - 2019</th>";
        $content .= "</tr>";
      $content .= "</thead>";
      $content .= "<thead>";
        $content .= "<tr>";
          $content .= "<th>Period</th>";
          $content .= "<th>1</th>";
          $content .= "<th>2</th>";
          $content .= "<th>3</th>";
          $content .= "<th>4</th>";
          $content .= "<th>5</th>";
          $content .= "<th>6</th>";
          $content .= "<th>7</th>";
          $content .= "<th>8</th>";
          $content .= "<th>9</th>";
          $content .= "<th>10</th>";
          $content .= "<th>11</th>";
          $content .= "<th>12</th>";
          $content .= "<th>Total</th>";
        $content .= "</tr>";
      $content .= "</thead>";
      $label = ["Present", "Absent", "Late"];
      foreach($label as $l) {
        $content .= "<tbody>";
        $content .= "<tr>";
        $content .= "<td>".$l."</td>";
        for($i = 1; $i <= 12; $i ++) {
          $content .= "<td>".Core\Calendar::getAttendance($l, $i)."</td>";
        }
        $content .= "<td></td>";
        $content .= "</tr>";
        $content .= "</tbody>";
      }
    $content .= "</table>";
    echo $content;
  }
}

if(($_POST["requestType"]) == "requestInfo") {
  if(isset($_POST["idnumber"]) && ($_POST["idnumber"]) != "") {
    $id = $_POST["idnumber"];
    $results = Core\db::query(array("SELECT idnumber, name FROM preschool WHERE idnumber = ?", array($id)));
    $results = $results[0];

    echo $content;
  }
}

if(($_POST["requestType"]) == "requestAttendance" && isset($_POST["idnumber"]) && $_POST["idnumber"] != "") {
  $idnumber = $_POST["idnumber"];
  echo json_encode(Core\Attendance::requestAttendance($idnumber));
}
?>
