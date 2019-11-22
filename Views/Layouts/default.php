<!doctype html>
<head>
    <meta charset="utf-8">

    <title>Gatekeeper</title>
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
        <div class="dropdown">
            <span>Attendance</span>
            <div class="dropdown-content">
                <div class="menu-label"><a href="/attendance/">Attendance</a></div>
                <hr>
                <div><a href="/attendance/search/">Search by Student</a></div>
                <!-- <div><a href="/attendance/view/">View (Dev)</a></div> -->
            </div>
        </div>
        <div class="dropdown">
            <span>Management</span>
            <div class="dropdown-content">
                <div class="menu-label"><a href="/student/">Student Management</a></div>
                <hr>
                <!-- <div><a href="/student/list/">List All Students</a></div> -->
                <div><a href="/student/create/">Add Student</a></div>
                <div><a href="/student/modify/">Modify Student Info</a></div>
            </div>
        </div>
        <!-- <div class="dropdown">
            <span>Database</span>
            <div class="dropdown-content">
                <div class="menu-label"><a href="/database/">DB Management</a></div>
                <hr>
                <div><a href="/database/controls/">Controls</a></div>
            </div>
        </div> -->
    </div>
        <?php echo $content_for_layout; ?>
</body>
</html>
