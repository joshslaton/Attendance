<?php
namespace Core;

class Pages {

  public static function getContents($path, $isRendered=False) {
    ob_start();
    require_once($path);
    $html = ob_get_contents();
    // $html = ob_end_clean();
    ob_end_clean();
    if($isRendered) {
      Registry::set("html", $html);
      self::renderer();
    } else {
      echo $html;
    }
  }

  public static function renderer() {
    include("./themes/default/header.php");
    echo Registry::get("html");
    include("./themes/default/footer.php");
  }
}
