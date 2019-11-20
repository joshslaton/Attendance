<?php

$html = "";
$html = "" .
  "<div data-script=\"test\">" .
    "<table class=\"table table-sm w-auto\" style=\"margin: 0 auto;\">" .
    "<tr rowspan=2>" .
      "<th><center>Reset</center></th>" .
    "</tr>" .
    "<tr>" .
      "<td>Attendance Table</td>" .
      "<td>" .
        "<input id=\"r1\" class=\"btn btn-success\" type=\"button\" value=\"Click!\">" .
      "</td>" .
    "</tr>" .
    "</table>" .
  "</div>" .
  "";
echo $html;
