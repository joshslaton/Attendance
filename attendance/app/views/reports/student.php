<?php
$content = '<div class="page-content" style="width: 100%;">';

$content .= '<div class="form-group searchStudent" style="margin: 0 auto;">';
$content .= '<label for="searchStudent">Student ID: </label>';
$content .= '<input class="form-control" id="searchStudent" type="text">';
$content .= '<button type="submit" class="btn btn-primary">Submit</button>';
$content .= '</div>';

$content .= '<caption>Legend:</caption>';
$content .= '<table class="table table-bordered" style="width: auto; margin: 10px;">';
$content .= '<tr style="float: left; margin: 5px;">';
$content .= '<td class="green">&nbsp</td>';
$content .= '<td>-> Has "IN" and "OUT" Record</td>';
$content .= '</tr>';

$content .= '<tr style="float: left; margin: 5px;">';
$content .= '<td class="yellow">&nbsp</td>';
$content .= '<td>-> Has "IN" or "OUT" Record</td>';
$content .= '</tr>';

$content .= '<tr style="float: left; margin: 5px;">';
$content .= '<td class="red">&nbsp</td>';
$content .= '<td>-> No Record at All</td>';
$content .= '</tr>';

$content .= '<tr style="float: left; margin: 5px;">';
$content .= '<td>&nbsp</td>';
$content .= '<td>-> Weekend</td>';
$content .= '</tr>';

$content .= '</table>';

if(isset($_GET['idnumber'])){
  $idnumber = $_GET['idnumber'];
  $q = "SELECT CONCAT(year(time_recorded),'-',month(time_recorded),'-',day(time_recorded)) as DDATE, SUM(IF(direction='in',1,0)) AS DIN, SUM(IF(direction='out',1,0)) AS DOUT FROM gatekeeper_in WHERE idnumber = ? GROUP BY DDATE";

  $studentDateRecord = Core\DB::query(array($q,array($idnumber)));
  // $content = '<div class="page-content" style="width: 100%;">';

  for($i = 1; $i<=12; $i++)
    $content .= Core\Calendar::build_calendar($i, 2019, $studentDateRecord);

}
$content .= '</div>';
echo $content;


?>
