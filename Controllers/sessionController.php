<?php
class Session {

  function c($var) {
    if(isset($var) && $var != "") {
      return true;
    }
    return false;
  }
}
?>
