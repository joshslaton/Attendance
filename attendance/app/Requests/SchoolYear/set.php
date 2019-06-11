<?php
if(isset($_GET["schoolYear"]) && $_GET["schoolYear"] != '') {
  $prev = $_SERVER["HTTP_REFERER"];
  if($_SESSION["schoolYear"] != $_GET["schoolYear"]){
    $_SESSION["schoolYear"] = $_GET["schoolYear"];
    echo "Set";
    header("Location: ".$prev);
  } else {
    header("Location: ".$prev);
  }
}
