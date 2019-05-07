<?php
namespace Core;

class Pages {

  function __construct(){

  }

  function __destruct(){

  }

  function renderPage(){
    $themeDir = Registry::get('config/dir/themes') . DIRECTORY_SEPARATOR . Registry::get('config/theme');
    $headerFilepath = $themeDir . DIRECTORY_SEPARATOR . 'header.php';
    if (file_exists($headerFilepath) && is_file($headerFilepath)) {
      require_once($headerFilepath);
    }

    if (Registry::check('page_html')) {
      echo Registry::get('page_html');
    }

    $footerFilepath = $themeDir . DIRECTORY_SEPARATOR . 'footer.php';
    if (file_exists($footerFilepath) && is_file($footerFilepath)) {
      require_once($footerFilepath);
    }
  }
}
?>
