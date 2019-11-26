<div id="pageBody">
  <div id="divStudentSearch" class="center" data-script="searchStudent">
      <form method="post" action="/attendance/search/">
          Student Search:
          <input type="text" name="idnumber" id="searchStudent">
          <input type="submit" value="Search and Display">
      </form>
  </div>

  <div id="attendanceSearch" class="container">
  <?php

    function inList($list, $idnumber) {
      foreach($list as $l) {
        if(in_array($idnumber, $l))
          return true;
      }
      return false;
    }

    if(isset($_POST["idnumber"]) && $_POST["idnumber"] != "" && inList($studentList, $_POST["idnumber"])) {
      $idnumber = $_POST["idnumber"];
      $date = new DateTime();

      $html = "";
      $html .= "<form method=\"post\" action=\"/attendance/view/\">";
        $html .= "<div class=\"form-group\">";
          $html .= "<input type=\"hidden\" name=\"idnumber\" value=\"$idnumber\">";
          $html .= "<label for=\"optionYear\">Year: </label>";
          $html .= "<select id=\"optionYear\" class=\"form-control\" name=\"optionYear\">";
            $html .= "<option value=\"2018\">2018</option>";
            $html .= "<option value=\"2019\">2019</option>";
          $html .= "</select>";

          $html .= " | <label for=\"optionMonth\">Month: </label>";
          $html .= "<select id=\"optionMonth\" class=\"form-control\" name=\"optionMonth\">";
          for($m = 1; $m <= 12; $m++) {
            $date = new DateTime("2019-$m-1");
            $html .= "<option value=\"$m\">".$date->format("F")."</option>";
          }
          $html .= "</select>";
          $html .= "</div>";
          $html .= "<input class=\"btn btn-primary\" type=\"submit\" value=\"Generate\">";
        $html .= "</form>";
      echo $html;
    }
    ?>
  </div>
</div>
