<?php
namespace Core;

class db {

  public static $connection;

  function __construct() {}
  function __destruct() {}

  public static function connect() {
    try {

      $config = Registry::get('config/database');

      self::$connection = new \PDO('mysql:host='.$config["host"].'; dbname='. $config["database"], $config["username"], $config['password']);

    } catch( \PDOexception $e) {
      echo $e->getMessage();
      exit;

    }
  }

  public static function query($args) {
    if(empty(self::$connection)) {
      self::connect();
    }
    $result = null;
    if(!is_array($args)) {
      error_log("DB::query is called with invalid parameters");
      return $result;
    }

    $query = "";
    $value = "";
    if (!empty($args[0])) {
      $query = !empty($args[0]) ? $args[0] : $args['query'];
    }
    if (!empty($args[1])) {
      $value = $args[1];
    }

    $queryType = explode(" ", $query);
    $queryType = strtolower($queryType[0]);

    try {
      print($value);
      $stmt = self::$connection->prepare($query);
      $execute = !empty($value) ? $stmt->execute($value) : $stmt->execute();
      if (in_array($queryType, array('select', 'show'))) {
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      } else if ($queryType, array('insert', 'update', 'delete')) {
        // ???
      }
    } catch ( \PDOexception $e) {

    }

  }
} // End of class
?>
