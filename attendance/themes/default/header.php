<?php
  session_start();
  // $title = Core\Registry::get('config/page_title');
  $html = "<html>";
  $html .= "<head>";
  $html .= "<title>".Core\Registry::get("config/page_title")."</title>";
  $html .= "<script src=\"".Core\Registry::get("config/url/base"). "plugins/jquery.min.js\" type=\"text/javascript\"></script>";
  $html .= "<script src=\"".Core\Registry::get("config/url/base"). "plugins/bootstrap.min.js\" type=\"text/javascript\"></script>";
  $html .= "";
  $html .= "<link href=\"".Core\Registry::get("config/url/base"). "plugins/bootstrap.min.css\" rel=\"stylesheet\">";
  $html .= "<script src=\"".Core\Registry::get("config/url/theme"). "scripts.js\"></script>";
  $html .= "<link href=\"".Core\Registry::get("config/url/theme"). "style.css\" rel=\"stylesheet\">";
  $html .= "</head>";
  $html .= "<body>";
  $html .= "<div class=\"page-wrapper\">";

  echo $html;

  $menu = "<div class=\"page-header noselect\" style=\"background-color: rgb(0, 0, 0, 0);\">";

    // $menu .= '<div class="dropdown">';
    // $menu .= '<span><a href="'.Core\Registry::get('config/url/base').'views/home/">Home</a></span>';
    // $menu .= '</div>';
    $menu .= "<div class=\"dropdown\">";
    $menu .= "<span><a href=\"".Core\Registry::get("config/url/base")."views/reports/\">Reports</a></span>";
      $menu .= '<div class="dropdown-content">';
        // $menu .= '<div><a href="'.Core\Registry::get('config/url/base').'views/reports/student/">Student</a></div>';
        // $menu .= '<div><a href="'.Core\Registry::get('config/url/base').'views/reports/section/">Section</a></div>';
        // $menu .= '<div><a href="'.Core\Registry::get('config/url/base').'views/reports/grade/">Grade</a></div>';
      $menu .= '</div>';
    $menu .= '</div>';

    $schoolYear = Core\db::query(array("SELECT name, syear FROM proj_sy WHERE isActive = 1", array()));
    if(sizeof($schoolYear) > 0) {
      $menu .= '<div class="dropdown">';
      $menu .= '<span><a href="'.Core\Registry::get('config/url/base').'views/reports/">School Year</a></span>';
        $menu .= '<div class="dropdown-content">';
        foreach($schoolYear as $year) {
          $menu .= "<div><a href='".Core\Registry::get("config/url/base")."Requests/SchoolYear/set/?schoolYear=".$year["syear"]."'>".$year["name"]."</a></div>";
        }
        $menu .= '</div>';
      $menu .= '</div>';

      $menu .= "<div class=\"dropdown\">";
      $menu .= "<span>Logout</span>";
      $menu .= "</div>";
    }

    $menu .= "<div class='schoolYear' data-script='schoolYear'>";
    if(!isset($_SESSION["schoolYear"])) {
      if($results = Core\db::query(array("SELECT * FROM proj_sy WHERE isDefault = 1"))) {
        $results = $results[0];
        $_SESSION["schoolYear"] = $results["syear"];
        $menu .= "Current School Year: ".$results["syear"];
      } else {
        $_SESSION["schoolYear"] = date("Y");
      }
    } else {
      $menu .= "Current School Year: ".$_SESSION["schoolYear"];
    }
      $menu .= "<div class='schoolYearOptions'>";
      $menu .= "<label for=\"termPeriod\" class=\"schoolYearOptionsLabel\">Start - End: </label>";
      $menu .= "<input id=\"termPeriod\" type=\"text\" style='width: 75px;'> - <input type=\"text\" style='width: 75px;'></input>";
      $menu .= "<span class=\"setButton\">Set</span>";
      $menu .= "</div>";
    $menu .= "</div>";

  $menu .= "</div>"; // End of Page Header Div

  echo $menu;
?>
