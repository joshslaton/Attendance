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
        <?php
        $stylesheet = file_get_contents(dirname(__FILE__, 3) . "/Plugins/timecard.css");
        require_once(dirname(__FILE__, 3) . "/Plugins/composer/vendor/autoload.php");
        $mpdf = new \Mpdf\Mpdf();
        // $mpdf->WriteHTML($content_for_layout, 2);
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($content_for_layout,\Mpdf\HTMLParserMode::HTML_BODY);
        // $mpdf->Output();
        echo $content_for_layout;
        ?>
</body>
</html>
