<?php

class Router {

  public static $request;
  public static $routes = [];

  public static function init(array $request) : void {
    if(!array_key_exists("REDIRECT_URL", $request)) {
      self::$request = "/home";
    }else {
      self::$request = $request["REDIRECT_URL"];
    }
  }

  public static function addRoute(string $uri, \Closure $fn) : void {
    self::$routes[$uri] = $fn;
  }

  public static function hasRoute(string $uri) : bool {
    return array_key_exists($uri, self::$routes);
  }

  public static function run() {
    if(self::hasRoute(self::$request)) {
      self::$routes[self::$request]();
    } else {
      include("./views/404.php");
    }
  }
}

$bootstrapFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . "bootstrap.php";
if(is_file($bootstrapFile) && file_exists($bootstrapFile)) {
  include_once($bootstrapFile);
  Router::init($_SERVER);
  Router::addRoute('/admin', function() { include("./views/admin.php"); });
  Router::addRoute('/home', function() { include("./views/home.php"); });
  Router::addRoute('/home/test', function() { include("./views/home/test.php"); });
  Router::addRoute('', function() { include("./views/home.php"); });
  Router::run();
}
