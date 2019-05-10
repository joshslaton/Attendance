<?php
namespace Core;

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

namespace Test;
class Calendar {

    // private static $m = array(
    //
    // );
    function __construct() {}
    function __destruct() {}

    public static $month = 0;
    public static $year = 0;
    public static $monthArray = Array();
    public static $currentDay = 0;
    public static $daysInmonth = 0;

    public static function build($month, $year) {
      self::$currentDay = 0;
      self::$month = $month;
      self::$year = $year;

      // self::$daysInmonth = date('t', mktime(0, 0, 0, self::$month, 1, self::$year));
      self::$daysInmonth = date('t', strtotime(self::$year.'-'.self::$month.-'01'));

      $numOfWeeks = (self::$daysInmonth%7==0 ? 0 : 1)+intval(self::$daysInmonth/7);
      $monthEndingDay = date('N', strtotime(self::$year.'-'.self::$month.'-'.self::$daysInmonth));
      $monthStartingDay = date('N', strtotime(self::$year.'-'.self::$month.'-01'));
      // print_r($monthEndingDay);
      // print_r($monthStartingDay);
      if($monthEndingDay < $monthStartingDay) {
        $numOfWeeks++;
      }

      for($i = 0; $i < $numOfWeeks; $i++) {
        self::$monthArray["Row".$i] = array();
        for($j = 0; $j < 7; $j++) {
          // print_r($i*7+$j);
          array_push(self::$monthArray["Row".$i], self::showDay($i*7+$j));
        }
      }
      // echo "<pre>";
      // print_r(self::$monthArray);
      // echo "</pre>";
      // print_r(self::$currentDay);
      return self::$monthArray;

    }

    public static function show() {
      $dayLabels = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
      $cal = '';
      $cal .= '<table class="table table-bordered" style="width:auto; text-align: center; float:left; margin:10px;">';
      $cal .= '<thead class="thead-dark">';
      $cal .= '<tr><th colspan=7>'.date("F", mktime(0, 0, 0, self::$month, 1, self::$year)).'</th></tr>';
      $cal .= '</thead>';
      $cal .= '<tr>';
      foreach($dayLabels as $dow){
        $cal .= '<td>'.$dow.'</td>';
      }
      $cal .= '</tr>';
      foreach(self::$monthArray as $weekRows) {
        $cal .= '<tr>';
        $count = 0;
        foreach($weekRows as $cell) {
          if(!empty($cell)) {
            $cal .= '<td>'.$cell.'</td>';
          }else {
            $cal .= '<td></td>';
          }
          $count ++;
        }
        $cal .= '</tr>';
      }
      $cal .= '</table>';

      return $cal;
    }

    private static function showDay($c) {
      if(self::$currentDay == 0) {
        $firstDayOfTheWeek = date('N', strtotime(self::$year.'-'.self::$month.'-1'));
        if($firstDayOfTheWeek == $c) {
          self::$currentDay = 1;
          return self::$currentDay;
        }
      }
      print(self::$currentDay);
      if((self::$currentDay != 0) && (self::$currentDay <= self::$daysInmonth)) {
        self::$currentDay++;
        // return self::$currentDay;
      }else{
        return 0;
      }
      return self::$currentDay;
    }
}
