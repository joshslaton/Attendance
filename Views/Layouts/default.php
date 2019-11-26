<!doctype html>
<head>
  <meta charset="utf-8">

  <title>Gatekeeper</title>
  <link type="text/css" href="/Plugins/normalize.css" rel="stylesheet">
  <script type="text/javascript" src="/Plugins/jquery.min.js"></script>
  <link type="text/css" href="/Plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
  <script type="text/javascript" src="/Plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <link type="text/css" href="/Plugins/bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" src="/Plugins/bootstrap.min.js"></script>
  <link type="text/css" href="/Plugins/style.css" rel="stylesheet">
  <script type="text/javascript" src="/Plugins/scripts.js"></script>
  <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">
  <body>
    <div class="page-header">
      <span class="page-title no-select">RFID Attendance Management | </span>
      <?php

      if (isset($_SESSION["isLoggedIn"])) {
        if ($_SESSION["isLoggedIn"] == 1) {
          $menu = "";
          $menu .= "" .
          "<div class=\"dropdown\">" .
          "<span><a href=\"/\">Home</a></span>" .
          "</div>";
          echo $menu;
          echo "<span style=\"float: right;\"><a href=\"/user/logout/\">Logout</a></span>";
        }
      }
      ?>
    </div>
    <?php echo $content_for_layout; ?>
  </body>
  </html>
