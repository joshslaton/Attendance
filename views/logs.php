<?php
$d = new DateTime();
$d = $d->format("Y-m-d");

// $yearlevels = Core\db::query(array("SELECT DISTINCT ylevel FROM proj_student2 WHERE ylevel NOT IN (\"FACULTY\", \"STAFF\")"));
$yearlevels = Core\db::query(array("SELECT DISTINCT ylevel FROM proj_student2"));
$html = "";
$html .= "<div class=\"page-content\" style=\"width: 100%; height: calc(100% - 34px);\">";
$html .= "<h4>Date: $d</h4>";
foreach($yearlevels as $yl) {
    $html .= "<div style=\"float: left; margin-right: 20px;\">";
    $html .= "<h4>" . $yl["ylevel"] ."</h4>";
    
        // With Attendance
        $withAttendance = Core\db::query(
            array(
                "SELECT " .
                "proj_student2.idnumber, " .
                "CONCAT(proj_student2.fname, \" \",proj_student2.lname) as name " .
                "from proj_student2 WHERE ylevel = \"". $yl["ylevel"]."\" AND idnumber IN " .
                "(SELECT idnumber FROM proj_attendance WHERE time BETWEEN \"$d 00:00:00\" AND \"$d 23:59:59\")"
            )
        );
        $html .= "<div>";
        $html .= "<p><b>With Attendance (".count($withAttendance).")</b></p>";
        foreach($withAttendance as $w) {
            $html .= "<p>" . $w["idnumber"] . " - " . $w["name"] . "</p>";
        }
        $html .= "</div>";

        // Without Attendance
        $withoutAttendance = Core\db::query(
            array(
                "SELECT " .
                "proj_student2.idnumber, " .
                "CONCAT(proj_student2.fname, \" \",proj_student2.lname) as name " .
                "from proj_student2 WHERE ylevel = \"". $yl["ylevel"]."\" AND idnumber NOT IN " .
                "(SELECT idnumber FROM proj_attendance WHERE time BETWEEN \"$d 00:00:00\" AND \"$d 23:59:59\")"
            )
        );
        $html .= "<div>";
        $html .= "<p><b>Without Attendance (".count($withoutAttendance).")</b></p>";
        foreach($withoutAttendance as $w) {
            $html .= "<p>" . $w["idnumber"] . " - " . $w["name"] . "</p>";
        }
        $html .= "</div>";

    $html .= "</div>";
}
$html .= "</div>";
echo $html;