<?php
include("../Models/DB.php");
include("../Models/Attendance.php");
$attendance = new Attendance();
if(!is_null($_GET["dir"]) && !is_null($_GET["idnumber"])) {
    $idnumber = $_GET["idnumber"];
    $gate = $_GET["dir"];
    $time = new DateTime();
    $schoolYear = 2019;
    $attendance->insert($idnumber, $gate, $time->format("Y-m-d H:i:s"), $schoolYear);
}
