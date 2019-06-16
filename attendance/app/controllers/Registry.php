<?php
namespace Core;

class Registry{
  public static $registry;

  function __construct () {

  }

  function __destruct () {

  }

  public static function check ($var) {
    // What do we need to check ?
    if( !is_array (self::$registry)){
      self::$registry = array ();
    }
    $var = explode ("/", trim($var,"/ "));
    $val = "";
    $exists = false;
    foreach ($var as $a => $b){
      if ($a > 0) {
        if (isset ($val[$b])) {
          $val = $val[$b];
          $exists = true;
        }
        else {
          $exists = false;
        }
        continue;
      }

      if (isset (self::$registry[$b])) {
        $val = self::$registry[$b];
        $exists = true;
      } else {
        $exists = false;
        break;
      }
    }
    return $exists;
  }

  public static function get ($var) {
    if(!is_array (self::$registry)) {
      self::$registry = array();
    }
    $var = explode ("/", trim($var, "/ "));
    $val = "";
    foreach ($var as $a => $b) {

      if ($a > 0) {

          if (isset($val[$b])) {
              $val = $val[$b];
          } else {
              echo 'An invalid registry variable is being called.';
              error_log('Registry `' . $b . '` is being accessed but it doesn\'t exists.', 0);
              exit;
              break;
          }
          continue;
      }
      if (isset(self::$registry[$b])){
        $val = self::$registry[$b];
      } else {
        echo 'An invalid registry variable is being called.';
        error_log('Registry `' . $b . '` is being accessed but it doesn\'t exists.', 0);
        exit;
        break;
      }
    }
    return $val;
  }

  public static function set ($var, $val) {
    if (!is_array (self::$registry)) {
      self::$registry = array();
    }
    self::$registry[$var] = $val;

  }


}
?>
