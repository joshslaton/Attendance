<?php
$config = array();
$config['page_title'] = 'Attendance Monitoring';

$config['debug'] = true;
$config['theme'] = 'default';

$config['url'] = array();
$config['url']['http'] = 'http://';
// $config['url']['base'] = $config['url']['http'] . trim($_SERVER["HTTP_HOST"],"/ ") . "/" .
// trim(basename(dirname(dirname(__FILE__))), "/ ") . '/';
$config['url']['base'] = $config['url']['http'] . trim($_SERVER["HTTP_HOST"],"/ ") . "/";
$config['url']['theme'] = $config['url']['base'] . 'themes/' . $config['theme'] . '/';


$config['dir'] = array();
$config['dir']['root'] = dirname(dirname(__FILE__));
$config['dir']['app'] = $config['dir']['root'] . DIRECTORY_SEPARATOR . 'app';
$config['dir']['themes'] = $config['dir']['root'] . DIRECTORY_SEPARATOR . 'themes';
$config['dir']['plugins'] = $config['dir']['root'] . DIRECTORY_SEPARATOR . 'plugins';


$config['database'] = array(
  'host' => 'localhost',
  'username' => 'kiosk',
  'password' => 'kiosk',
  'database' => 'preschool_gatekeeper'
);

$config['filesWithoutHeader'] = array(
  "studentInfo.php",
  "loadStudentTable.php",
  "arduino_1sms.php",
  "arduino_app.php",
  "arduino_gatecheck.php",
);

Core\Registry::set('config', $config); # Whats the purpose of this?
?>