<?php
  // session_start();
  // $title = Core\Registry::get('config/page_title');

  $html = '<html>';
  $html .= '<head>';
  // $html .= '<title>'.Core\Registry::get('config/page_title').'</title>';
  $html .= '<script src="'.Core\Registry::get('config/url/base'). 'plugins/jquery.min.js" type="text/javascript"></script>';
  $html .= '<script src="'.Core\Registry::get('config/url/base'). 'plugins/bootstrap.min.js" type="text/javascript"></script>';
  $html .= '';
  $html .= '<link href="'.Core\Registry::get('config/url/base'). 'plugins/bootstrap.min.css" rel="stylesheet">';
  $html .= '<script src="'.Core\Registry::get('config/url/theme'). 'scripts.js"></script>';
  $html .= '<link href="'.Core\Registry::get('config/url/theme'). 'style.css" rel="stylesheet">';
  $html .= '</head>';
  $html .= '<body>';
  $html .= '<div class="page-wrapper">';

  echo $html;

  $menu = '<div class="page-header" style="background-color: rgb(0, 0, 0, 0);">';

    // $menu .= '<div class="dropdown">';
    // $menu .= '<span><a href="'.Core\Registry::get('config/url/base').'views/home/">Home</a></span>';
    // $menu .= '</div>';

    $menu .= '<div class="dropdown">';
    $menu .= '<span><a href="'.Core\Registry::get('config/url/base').'views/reports/">Reports</a></span>';
      $menu .= '<div class="dropdown-content">';
        // $menu .= '<div><a href="'.Core\Registry::get('config/url/base').'views/reports/student/">Student</a></div>';
        // $menu .= '<div><a href="'.Core\Registry::get('config/url/base').'views/reports/section/">Section</a></div>';
        // $menu .= '<div><a href="'.Core\Registry::get('config/url/base').'views/reports/grade/">Grade</a></div>';
      $menu .= '</div>';
    $menu .= '</div>';

    $menu .= '<div class="dropdown">';
    $menu .= '<span><a href="'.Core\Registry::get('config/url/base').'views/reports/">School Year</a></span>';
      $menu .= '<div class="dropdown-content">';
        $menu .= '<div><a href="'.Core\Registry::get('config/url/base').'views/reports/student/">2018 - 2019</a></div>';
        $menu .= '<div><a href="'.Core\Registry::get('config/url/base').'views/reports/student/">2019 - 2020</a></div>';
      $menu .= '</div>';
    $menu .= '</div>';

    // $menu .= '<div class="dropdown">';
    // $menu .= '<span>Logout</span>';
    // $menu .= '</div>';

  $menu .= '</div>'; // End of Page Header Div
  // $menu .= '<div class="page-content">'; // End of Page Header Div

  echo $menu;
?>
