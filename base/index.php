<?php
namespace Core;
header("Access-Control-Allow-Origin: https://lcaccess.lorma.edu/api/");
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

$basedir = dirname(__FILE__);
$bootstrapFile = $basedir . DIRECTORY_SEPARATOR . "bootstrap.php";
if(is_file($bootstrapFile) && file_exists($bootstrapFile)) {
  include_once($bootstrapFile);
  Router::init($_SERVER);
  Router::addRoute('', function() { Pages::getContents(dirname(__FILE__) . "/views/home.php", True); });
  Router::addRoute('/home', function() { Pages::getContents(dirname(__FILE__) . "/views/home.php", True); });
  Router::addRoute('/admin', function() { Pages::getContents(dirname(__FILE__) . "/views/admin.php", True); });
  Router::addRoute('/Modules/requests', function() { Pages::getContents(dirname(__FILE__) . "/controllers/Modules/requests.php", False); });
  Router::run();
}
