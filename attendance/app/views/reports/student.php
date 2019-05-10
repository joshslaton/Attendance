<?php
$content = '<div class="page-content" style="width: 100%;">';
Test\Calendar::build(9, 2019);
$content .= Test\Calendar::show();
Test\Calendar::build(10, 2019);
$content .= Test\Calendar::show();
echo $content;
$content .= '</div>';

?>
