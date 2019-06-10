<?php
$y = 2019;

for($i = 1; $i <= 12; $i++) {
  $numOfDays = date("t", mktime(0, 0, 0, $i, 1, $y));
  $timeIn = "08:00:00";
  $timeOut = "17:00:00";
  for($j = 1; $j <= $numOfDays; $j++) {
    $dow = date("N", mktime(0, 0, 0, $i, $j, $y));
    if($dow <= 5){
      $i = strlen($i) == 1 ? str_pad($i, 2,"0",STR_PAD_LEFT) : $i;
      $j = strlen($j) == 1 ? str_pad($j, 2,"0",STR_PAD_LEFT) : $j;
      $dateIn = $y."-".$i."-".$j." ".$timeIn;
      $dateOut = $y."-".$i."-".$j." ".$timeOut;

      // echo $dateIn;
      // echo "<br>";
      // $results1 = Core\db::query("INSERT INTO proj_attendance (idnumber, gate, time, syear, remarks) VALUES (?, ?, ?, ?, ?)", array("2900876", "in", $dateIn, "2018", "Time-in"));
      // if($results1){
      //   echo $dateIn;
      //   echo "<br>";
      // }
      // echo $dateOut;
      // echo "<br>";
      $q = "INSERT INTO proj_attendance (`idnumber`, `gate`, `time`, `syear`, `remarks`) VALUES ('2900876', 'in', '$dateIn', '2018', 'Time-in');";
      $w = "INSERT INTO proj_attendance (`idnumber`, `gate`, `time`, `syear`, `remarks`) VALUES ('2900876', 'out', '$dateOut', '2018', 'Time-out');";
      echo $q."<br>";
      echo $w."<br>";
      // $results2 = Core\db::query("INSERT INTO proj_attendance ('idnumber', 'gate', 'time', 'syear', 'remarks') VALUES (?, ?, ?, ?, ?)", array("2900876", "out", "'".$dateOut."'", "2018", "Time-out"));
      // if($results2){
      //   echo $dateOut;
      //   echo "<br>";
      // }
    }
  }
}
