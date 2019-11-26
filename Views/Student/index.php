<?php
function replace_char($str) {
  return preg_replace("/(?:[A-Za-z]){1}/", "*", $str);
}

function show($students) {
  $html = "";
  $html = "<div id=\"pageBody\">";
  $html .= "" .
  "<table id=\"student-table\">" .
  "<tr>" .
  "<th>ID number</th>" .
  "<th>Last Name</th>" .
  "<th>First Name</th>" .
  "<th>Middle Name</th>" .
  "<th>Contact</th>" .
  "</tr>";
  foreach($students as $s) {
    $html .= "" .
    "<tr>" .
    "<td>" . $s["idnumber"] . "</td>" .
    "<td>" . replace_char($s["lname"]) . "</td>" .
    "<td>" . $s["fname"] . "</td>" .
    "<td>" . $s["mname"] . "</td>" .
    "<td>" . "**********" . "</td>" .
    // "<td>" . $s["contact"] . "</td>" .
    "<tr>";
  }
  $html .= "</table>";
  $html .= "</div>";

  echo $html;
}

switch($action) {
  case "show": show($students); break;
  default: break;
}
?>
