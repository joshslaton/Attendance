<?php
$bootstrapFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
if (file_exists($bootstrapFile) && is_file($bootstrapFile)) {
    require_once($bootstrapFile);
} else {
    echo 'An important system file is missing. Please contact your I.T. personnel to look into this error.';
    error_log($bootstrapFile . ' is missing.', 0);
    exit;
}
$routes = !empty($_GET['url']) ? explode("/", trim($_GET['url'], '/')) : array('Home');

if ($routes[count($routes) - 1] === 'index') {
    echo 'You\'re not allowed to point to index files.';
    exit;
}
$routePath = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'app'));
foreach ($routes as $route) {
    $routePath .= DIRECTORY_SEPARATOR . $route;
}
$routePath.='.php';
// print($routePath."<br>");
if(file_exists($routePath) && is_file($routePath)) {
  ob_start();
  require_once($routePath);
  $html = ob_get_contents();
  ob_end_clean();
  Core\Registry::set('page_html', $html);
  Core\Pages::renderPage();
}else {
    echo 'The webpage you\'re trying to access ' , $_SERVER['HTTP_HOST'] , $_SERVER['REQUEST_URI'] , ' doesn\'t exists.';
    error_log('Views file doesn\'t exists ' . $routePath, 0);
}
?>
