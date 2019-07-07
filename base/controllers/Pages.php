<?php
namespace Core;

class Pages {

  public static function getContents($path, $isRendered=False) {
    \ob_start();
    include_once($path);
    $html = \ob_get_contents();
    \ob_clean();

    echo ($isRendered) ? self::renderer($html) : $html;
  }

  public static function renderer($html) {
    include("./themes/default/header.php");
    echo $html;
    include("./themes/default/footer.php");
  }
}
