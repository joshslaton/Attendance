<?php
namespace Test;

class Calendar {

    function __construct() {}
    function __destruct() {}

    private static $currentDay = 0;
    private static $month;
    private static $year;
    private static $daysInmonth;
    private static $weeksInMonth;

    public static function init($m, $y) {
      self::$currentDay = 0;
      self::$month = $m;
      self::$year = $y;
      self::$daysInmonth = self::daysInMonth(self::$month, self::$year);
      self::$weeksInMonth = self::weeksInMonth(self::$month, self::$year);
    }

    public static function build() {
      $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
      $cal = '';

      $cal .= '<table class="table table-bordered" style="width: auto;float: left;">';

      // Current Month
      $cal .= '<tr>';
      $cal .= '<td class="center" colspan=7>'.self::$month.'</td>';
      $cal .= '</tr>';

      // Days of Week
      $cal .= '<tr>';
      foreach ($days as $day) {
        $cal .= '<td>'.$day.'</td>';
      }
      $cal .= '</tr>';

      // Days of Month
      for($i = 0; $i < self::weeksInMonth(self::$month, self::$year); $i++) {
        $cal .= '<tr>';
        for($j = 0; $j < 7; $j++) {
          $cal .= self::showDay($i*7+$j);
        }
        $cal .= '</tr>';
      }
      $cal .= '</table>';
      return $cal;
    }

    private static function showDay($cellNumber) {

      if(self::$currentDay == 0) {
        $firstDayOfTheWeek = date('N', strtotime(self::$year.'-'.self::$month.'-1'));

        if(intval($cellNumber) == intval($firstDayOfTheWeek)) {
          self::$currentDay = 1;
          return '<td>'.self::$currentDay.'</td>';
        }
      }
      if((self::$currentDay != 0) && (self::$currentDay < self::$daysInmonth)) {
         self::$currentDay++;
      }else{
        return '<td></td>';
      }
      return '<td>'.self::$currentDay.'</td>';
    }

    private static function daysInMonth($month, $year) {
      $dim = date("t", mktime(0, 0, 0, intval($month), 1, intval($year)));
      return $dim;
    }

    private static function weeksInMonth($month, $year) {

      $numOfWeeks = (self::$daysInmonth%7==0?0:1) + intval(self::$daysInmonth/7);
      // What is this
      $monthEndingDay = date('N', strtotime($year.'-'.$month.'-'.self::$daysInmonth));
      $monthStartingDay = date('N', strtotime($year.'-'.$month.'-01'));
      if($monthEndingDay < $monthStartingDay){
        $numOfWeeks++;
      }
      return $numOfWeeks;
    }
}

namespace Core;
class Calendar {

  function __construct() {}
  function __destruct() {}

  public static function build_calendar($month,$year,$s) {

     // Create array containing abbreviations of days of week.
     $daysOfWeek = array('S','M','T','W','T','F','S');

     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);

     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);

     // What is the name of the month in question?
     $monthName = $dateComponents['month'];

     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];

     // Create the table tag opener and day headers

     $calendar = "<table class='table table-bordered center' style='width: auto; float: left; margin: 10px;'>";
     $calendar .= "<thead class='thead-dark'><tr><th colspan=7>$monthName $year</th></tr></thead>";
     $calendar .= "<tr>";

     // Create the calendar headers

     foreach($daysOfWeek as $day) {
          $calendar .= "<th class='header'>$day</th>";
     }

     // Create the rest of the calendar

     // Initiate the day counter, starting with the 1st.

     $currentDay = 1;

     $calendar .= "</tr><tr>";

     // The variable $dayOfWeek is used to
     // ensure that the calendar
     // display consists of exactly 7 columns.

     if ($dayOfWeek > 0) {
          $calendar .= "<td colspan='$dayOfWeek'>&nbsp;</td>";
     }

     $month = str_pad($month, 2, "0", STR_PAD_LEFT);



     while ($currentDay <= $numberDays) {

        // Seventh column (Saturday) reached. Start a new row.

        if ($dayOfWeek == 7) {

             $dayOfWeek = 0;
             $calendar .= "</tr><tr>";

        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

        $date = "$year-$month-$currentDayRel";
        // $calendar .= "<td class='day' rel='$date'>$currentDay</td>";
        $calendar .= self::studentHasRecord($date, $s);

        // Increment counters

        $currentDay++;
        $dayOfWeek++;
      }




     // Complete the row of the last week in month, if necessary

     if ($dayOfWeek != 7) {

          $remainingDays = 7 - $dayOfWeek;
          // $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>";
          // self::studentHasRecord($s);

     }

     $calendar .= "</tr>";

     $calendar .= "</table>";

     return $calendar;

   }

  public static function studentHasRecord($date, $studentRecord) {
    $now = strtotime(date("Y-m-d", time()));
    foreach($studentRecord as $record) {
      if(in_array(date("Y-n-j", strtotime($date)), $record)){
        if($record["DIN"] > 0 && $record["DOUT"] > 0)
          return "<td class='day green' rel='$date'>".date("j",strtotime($date))."</td>";
        if($record["DIN"] > 0 && $record["DOUT"] == 0)
          return "<td class='day yellow' rel='$date'>".date("j",strtotime($date))."</td>";
        if($record["DIN"] == 0 && $record["DOUT"] > 0)
          return "<td class='day yellow' rel='$date'>".date("j",strtotime($date))."</td>";
        if(strtotime(time()) > strtotime($date))
          return "<td class='day red' rel='$date'>".date("j",strtotime($date))."</td>";
      }
    }
    if(!self::isWeekend($date)) {
      if($now > strtotime($date))
        return "<td class='day red' rel='$date'>".date("j",strtotime($date))."</td>";
      else
        return "<td class='day' rel='$date'>".date("j",strtotime($date))."</td>";
    } else {
      return "<td class='day' rel='$date'>".date("j",strtotime($date))."</td>";
    }
  }

  public static function isWeekend($date) {
    if(date("N", strtotime($date)) == 6 || date("N", strtotime($date)) == 7 )
      return True;

    return False;
  }

  public static function isHolday($date) {
    return True;
    // $holidays = [
    //   1 => Array(1 => "New Year's Day", 2 => "New Year's Holiday"),
    //   2 => Array(5 => "Chinese New Year", 25 => "People Power Revolution"),
    //   4 => Array(9 => "Araw ng Kagitingan"),
    //   4 => Array(9 => "Araw ng Kagitingan"),
    // ]
  }

  public static function isPresent($m, $d) {
    return True;
  }

  public static function getAttendance($type, $month) {
    $startDate = "2018-08-13";
    $endDate = "2019-05-17";
    $numOfDays = date("t", mktime(0, 0, 0, $month, 1));

    if($type == "Present") {
      $total = 0;
      for($i = 1; $i <= $numOfDays; $i++) {
        if(self::isPresent($month, $i)){
          $total += 1;
        }
      }
      return $total;
    }
    if($type == "Absent") {
      return 0;
    }
    if($type == "Late") {
      return 0;
    }
  }

}
