<?php
namespace Core;

class Registry {

  public static $registry;

  function set($var, $val) {
      if(!is_array(self::$registry)) {
        self::$registry = array();
      }
      self::$registry[$var] = $val;
  }

  function get($var) {
    echo (is_array(self::$registry)) ? \gettype(self::$registry) : \gettype(self::$registry);

    $var = explode("/", $var);
    if(!\array_key_exists($var[0], self::$registry)) {
      exit();
    }

    echo "<pre>";
    print_r(self::$registry);
    print_r($var);
    echo "</pre>";
  }
}
