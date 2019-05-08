<?php
  // $title = Core\Registry::get('config/page_title');

  $html = '<html>';
  $html .= '<head>';
  // $html .= '<title>'.Core\Registry::get('config/page_title').'</title>';
  $html .= '<script src="'.Core\Registry::get('config/url/theme'). 'scripts.js"></script>';
  $html .= '<link href="'.Core\Registry::get('config/url/theme'). 'style.css" rel="stylesheet">';
  $html .= '';
  $html .= '<script src="'.Core\Registry::get('config/url/base'). 'plugins/jquery.min.js"></script>';
  $html .= '<script src="'.Core\Registry::get('config/url/base'). 'plugins/bootstrap.min.js"></script>';
  $html .= '<link href="'.Core\Registry::get('config/url/base'). 'plugins/bootstrap.min.css" rel="stylesheet">';
  $html .= '</head>';
  $html .= '<body>';
  $html .= '<div class="page-wrapper">';

  // echo $html;

  $menu = '<div class="dropdown">';
  $menu .= '<span>Home</span>';
    $menu .= '<div class="dropdown-content">';
      $menu .= '<div>Sub menu 1</div>';
    $menu .= '</div>';
  $menu .= '</div>';

  // echo $menu;

  Core\db::query(array("SELECT * FROM preschool WHERE idnumber = ?",array("2900876")));
?>
