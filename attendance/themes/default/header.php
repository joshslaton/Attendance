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

  echo $html;

  $menu = '<div class="dropdown">';
  $menu .= '<span>Home</span>';
    $menu .= '<div class="dropdown-content">';
      $menu .= '<p>Sub menu 1</p>';
      $menu .= '<p>Sub menu 2</p>';
      $menu .= '<p>Sub menu 3</p>';
    $menu .= '</div>';
  $menu .= '</div>';

  echo $menu;
?>
