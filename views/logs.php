<?php
header("Refresh:5");
$d = new DateTime();
$d = $d->format("Y-m-d");
$query = "".
    "SELECT " .
    "proj_attendance.idnumber, " .
    "CONCAT(proj_student2.fname, \" \", proj_student2.lname) as name, " .
    "proj_attendance.time, " .
    "proj_attendance.gate " .
    "FROM proj_attendance " .
    "LEFT JOIN proj_student2 " .
    "ON proj_attendance.idnumber = proj_student2.idnumber " .
    "WHERE " .
    "proj_attendance.isSent = 1 AND " .
    "proj_attendance.time BETWEEN \"$d 00:00:00\" AND \"$d 23:59:59\" ".
    "ORDER BY proj_attendance.id DESC LIMIT 10";
$records = Core\db::query(array($query));
$html = "";
$html .= "<div class=\"page-content\" style=\"width: 100%; height: calc(100% - 34px);\">";
$html .= "<table class=\"table\" style=\"border: 0px; text-align: left;\">";
$html .= "<tr><td>ID Number - Name</td><td>Time</td><td>Gate</td></tr>";
if(count($records) > 0 ) {
    foreach($records as $record) {
        $t = new DateTime($record["time"]);
        $t = $t->format("Y-m-d h:i:s");
        $html .= "<tr>";
        $html .= "<td>" . $record["idnumber"] ."  ".$record["name"]. "</td>";
        $html .= "<td>" . $t . "</td>";
        $html .= "<td>" . $record["gate"] . "</td>";
        $html .= "</tr>";
    }
}
$html .= "</table>";
$html .= "</div>";
echo $html;
