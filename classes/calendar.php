<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DB.inc.php';

class calendar {
  private $schoolYear = Array(
    "start"=>"2018-08-13",
    "end"=>"2019-05-31"
  );



  public $holidays = Array(
    "January" => Array(1 => "New Year"),
    "February" => Array(5 => "Chinese New Year", 25 => "EDSA Revolution"),
    "April" => Array(9 => "Day of Valor", 18 => "Maundy Thursday", 19 => "Good Friday", 20 => "Black Saturday"),
    "May" => Array(1 => "Labor Day"),
    "June" => Array(12 => "Independence Day"),
    "August" => Array(21 => "Ninoy Aquino Day", 27 => "National Heroes Day"),
    "November" => Array(1 => "All Saints Day", 2 => "All Souls Day", 30 => "Bonifacio Day"),
    "December" => Array(24 => "Special Non-working Holiday", 25 => "Christmas Day", 31 => "Last Day of the Year"));

  function __construct(){
    $this->db = new db();
    $this->monthsToIterate = $this->monthsToIterate($this->schoolYear["start"], $this->schoolYear["end"]);
  }

  function build_calendar($month, $year) {
  	$daysOfWeek = array('S','M','T','W','T','F','S');
  	$firstDayOfMonth = mktime(0,0,0,$month,1,$year);
  	$numberDays = date('t',$firstDayOfMonth);
  	$dateComponents = getdate($firstDayOfMonth);
  	$monthName = $dateComponents['month'];
  	$dayOfWeek = $dateComponents['wday'];
  	$calendar = "<table class='calendar table table-condensed table-bordered'>";
  	$calendar .= "<div>$monthName $year</div>";
  	$calendar .= "<tr>";
  	foreach($daysOfWeek as $day) {
  		$calendar .= "<th class='header'>$day</th>";
  	}
  	$currentDay = 1;
  	$calendar .= "</tr><tr>";
  	if ($dayOfWeek > 0) {
  		$calendar .= "<td colspan='$dayOfWeek'>&nbsp;</td>";
  	}
  	$month = str_pad($month, 2, "0", STR_PAD_LEFT);
  	while($currentDay <= $numberDays){
  		if($dayOfWeek == 7){
  			$dayOfWeek = 0;
  			$calendar .= "</tr><tr>";
  		}
  		$currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
  		$date = "$year-$month-$currentDayRel";
  		// Is this today?
  		if(date('Y-m-d') == $date) {
  			$calendar .= "<td class='day success' rel='$date'><b>$currentDay</b></td>";
  		} else {
  			$calendar .= "<td class='day' rel='$date'>$currentDay</td>";
  		}
  		$currentDay++;
  		$dayOfWeek++;
  	}
  	if($dayOfWeek != 7){
  		$remainingDays = 7 - $dayOfWeek;
  		$calendar .= "<td colspan='$remainingDays'>&nbsp;</td>";
  	}
  	$calendar .= "</tr>";
  	$calendar .= "</table>";
  	return $calendar;
  }

  // TODO: Too many loops ? Not effiient ?
  function isHoliday($date){
    foreach($this->holidays as $month => $day){
      foreach($day as $d => $label){
        if($month == date("F", strtotime($date))){
          if($d == date("j", strtotime($date))){
            // echo "Holiday: $d - $label<br>";
            return True;
          }
        }
      }
    }
  }

  function daysInMonth($m){
    return date("t", mktime(0, 0, 0, $m, 1));
  }

  // TODO: Remove this soon
  function monthsToIterate($startDate, $endDate){
      $monthsToIterate = Array();
      $mStart = date("n", strtotime($startDate));
      $mEnd = date("n", strtotime($endDate));

      if($mStart > $mEnd){
        for($i=$mStart; $i<=12; $i++){
          array_push($monthsToIterate, $i." 2018");
        }
        for($j=1; $j<=$mEnd; $j++){
          array_push($monthsToIterate, $j." 2019");
        }
      }
      return $monthsToIterate;
  }

  function isWeekend($date){
    if(date("N", strtotime($date)) == 6 || date("N", strtotime($date)) == 7)
      return True;
  }

  function numberOfSchoolDays($startDate, $endDate){
    $numOfSchoolDays = 0;

    $begin = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = DateInterval::createFromDateString("1 day");
    $period = new DatePeriod($begin, $interval, $end);
    foreach($period as $p){
      if(!$this->isWeekend($p->format("Y-m-d")) && !$this->isHoliday($p->format("Y-m-d"))){
        $numOfSchoolDays += 1;
      }
    }

    return $numOfSchoolDays;
  }


  // TODO: efficient way to check attendance.
  function numberOfDaysPresent($studentID){
    $attendanceInfoArray = Array();
    $present = 0;
    $absent = 0;
    // $results = $this->db->query("SELECT DISTINCT month(`time_recorded`) as m ,concat(year(`time_recorded`),'-',month(`time_recorded`),'-',day(`time_recorded`)) as date FROM gatekeeper_in WHERE idnumber='".$studentID."'")->fetchAll();
    $begin = new DateTime($this->schoolYear["start"]);
    $end = new DateTime($this->schoolYear["end"]);
    $begin_unix = strtotime($begin->format("Y-n-j"));
    $begin_unix = strtotime($end->format("Y-n-j"));

    $now = date("Y-n-j");
    $now_unix = strtotime($now);

    $numOfschooldaysPassed = 0;
    $numOfschooldaysLeft = 0;

    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($begin, $interval, $end);

    $month = $this->db->query("SELECT DISTINCT month(`time_recorded`) as m  FROM gatekeeper_in WHERE idnumber='".$studentID."'")->fetchAll();

    $datesPresent = $this->db->query("SELECT DISTINCT concat(year(`time_recorded`),'-',month(`time_recorded`),'-',day(`time_recorded`)) as date FROM gatekeeper_in WHERE idnumber='".$studentID."'")->fetchAll();

    foreach ($period as $dt) { // foreach date
      if(strtotime($dt->format("Y-n-j")) <= $now_unix){
        if(!$this->isWeekend($dt->format("Y-n-j")))
          $numOfschooldaysPassed += 1;
        if(!$this->isWeekend($dt->format("Y-n-j")) && $this->isHoliday($dt->format("Y-n-j")))
          $numOfschooldaysPassed -= 1;
        foreach($datesPresent as $dp){
          // If theres a record
          if($dt->format("Y-n-j") == $dp["date"]){
            $present += 1;
          }
        }
      }else{
        $numOfschooldaysLeft += 1;
      }
    }
    $attendanceInfoArray[$studentID] = Array(
      "present" => $present,
      "absent" => ($numOfschooldaysPassed - $present)
    );
    // echo "<br>School Days: $numOfschooldaysPassed";
    // echo "<br>School Days Left: $numOfschooldaysLeft";
    // echo "<br>Present: $present";
    // echo "<br>Absent: ".($numOfschooldaysPassed - $present);

    return $attendanceInfoArray;

  }

  function numberOfDaysAbsent($studentID){
    $absent = 0;
    $now = date("Y-m-d");
    return $now;
  }

}
//
// $c = new calendar();
//
// $count = 0;
// $start = new DateTime("2018-08-13");
// $end = new DateTime("2019-05-31");
// // print("Number of school days: ".$c->numberOfSchoolDays($start->format("Y-m-d"), $end->format("Y-m-d")));
//
// print_r($c->numberOfDaysPresent("2900876"));
