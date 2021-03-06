<?php
class DB {

  public static $connection;
  private static $lastInsertId;

  function __construct() {}
  function __destruct() {}

  public static function connect() {
    $dbhost = "localhost";
    $dbname = "proj_gatekeeper";
    $dbuser = "gatekeeper";
    $dbpass = "esxivmware";
    try {
      // print_r(Registry::check('config'));
      // $config = Registry::get('config/database');
      self::$connection = new \PDO('mysql:host='.$dbhost.'; dbname='. $dbname, $dbuser, $dbpass, array('charset'=>'utf8'));
      // self::$connection = new \PDO('mysql:host='.$config["host"].'; dbname='. $config["database"], $config["username"], $config['password']);

    } catch( \PDOexception $e) {
      echo $e->getMessage();
      exit;

    }
  }

  public static function lastInsertId () {
      return self::$lastInsertId;
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
      $stmt = self::$connection->prepare($query);
      $execute = !empty($value) ? $stmt->execute($value) : $stmt->execute();
      if (in_array($queryType, array('select', 'show'))) {
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      } else if (in_array($queryType, array('insert', 'update', 'delete'))) {
        if ($queryType === 'insert') {
            self::$lastInsertId = self::$connection->lastInsertId();
        } else {
            self::$lastInsertId = null;
        }
        $result = $execute;
      }
    } catch ( \PDOexception $e) {
      print_r($e->message());
    }
    return $result;
  }
} // End of class
?>
