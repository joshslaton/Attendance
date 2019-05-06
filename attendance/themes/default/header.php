<?php
  // $title = Core\Registry::get('config/page_title');

  $html = '<html>';
  $html .= '<head>';
  $html .= '<title>'.Core\Registry::get('config/page_title').'</title>';
  $html .= '<script src="'.Core\Registry::get('config/url/theme'). 'scripts.js"></script>';
  $html .= '<link href="'.Core\Registry::get('config/url/theme'). 'style.css" rel="stylesheet">';
  $html .= '';
  $html .= '<script src="'.Core\Registry::get('config/url/base'). 'plugins/jquery.min.js"></script>';
  $html .= '<script src="'.Core\Registry::get('config/url/base'). 'plugins/bootstrap.min.js"></script>';
  $html .= '<link href="'.Core\Registry::get('config/url/base'). 'plugins/bootstrap.min.css" rel="stylesheet">';
  $html .= '</head>';
  $html .= '<body>';

  echo $html;
?>
