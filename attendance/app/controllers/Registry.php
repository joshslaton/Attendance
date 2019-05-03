<?php
namespace Core;

class Registry{
  public static $registry;

  function __construct() {

  }

  function __destruct() {

  }

  public static function set($var, $val) {
    if(!is_array(self::$registry)){
      self::$registry = array();
    }
    self::$registry[$var] = $val;
  }

  public static function get($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
  }

}
?>
