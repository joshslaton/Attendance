<?php
  // Calculate school days
  // Y-m-D
  $startDate = "2018-08-13";
  $endDate = "2019-05-31";
  $datediff = strtotime($endDate) - strtotime($startDate);
  print_r($datediff / (60 * 60 * 24));
?>
