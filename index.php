<?php
namespace Core;
session_start();
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
    // AttendanceModules/
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

  // --------------------------------MAIN PAGE------------------------------- //
  // var_dump(dirname(__FILE__) . "/views/reports.php");
  Router::addRoute(
    '',
    function() { Pages::getContents(dirname(__FILE__) . "/views/home.php", True); }
  );
  Router::addRoute(
    '/home/',
    function() { Pages::getContents(dirname(__FILE__) . "/views/home.php", True); }
  );
  Router::addRoute(
    '/reports/',
    function() { Pages::getContents(dirname(__FILE__) . "/views/reports.php", True); }
  );

  Router::addRoute(
    '/logs/',
    function () { Pages::getContents(dirname(__FILE__) . "/views/logs.php", True); }
  );


  // ----------------------------------MODULES------------------------------- //
  Router::addRoute(
    '/Modules/Student/AttendanceSheet/',
    function() {
      Pages::getContents(dirname(__FILE__) .
      "/controllers/Modules/Student/AttendanceSheet.php", False);
    }
  );
  Router::addRoute(
    '/Modules/Student/ListAllStudents/',
    function() { Pages::getContents(dirname(__FILE__) .
      "/controllers/Modules/Student/ListAllStudents.php", False);
    }
  );

  Router::addRoute(
    '/Modules/SchoolYear/Set/',
    function() { Pages::getContents(dirname(__FILE__) .
      "/controllers/Modules/SchoolYear/Set.php", False);
    }
  );

  Router::addRoute(
    '/SMS/',
    function() { Pages::getContents(dirname(__FILE__) .
      "/controllers/Modules/SMS.php", False);
    }
  );

  Router::addRoute(
    '/App/',
    function() { Pages::getContents(dirname(__FILE__) .
      "/controllers/Modules/App.php", False);
    }
  );


  // ----------------------------------RUN----------------------------------- //
  Router::run();
}
