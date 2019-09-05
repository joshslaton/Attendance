<?php
// For ajax call
if(!empty($_POST["term"])) {
  $term = $_POST["term"];
  if(preg_match("([0-9]{4}-[0-9]{4})", $term, $match)) {
    $_SESSION["schoolYear"] = explode("-", $match[0])[0];
    $_SESSION["term"] = $term;
  } else {

  }
}
