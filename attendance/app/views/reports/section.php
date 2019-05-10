<?php
 $sidebar = '<div class="page-sidebar">';

 $sidebar .= '<p class="options-title">Gender</p>';
 // Male
 $sidebar .= '<div class="form-inline">';
 $sidebar .= '<div class="form-group options-input">';
 $sidebar .= '<input name="gender" id="options-gender-m" class="options-sidebar" type="checkbox" value="m" />';
 $sidebar .= '<label name="gender" class="form-check-label" for="options-gender-m">Male</label>';
 $sidebar .= '</div>';
 $sidebar .= '</div>';
 // Female
 $sidebar .= '<div class="form-inline">';
 $sidebar .= '<div class="form-group options-input">';
 $sidebar .= '<input name="gender" id="options-gender-f" class="options-sidebar" type="checkbox" value="f" />';
 $sidebar .= '<label name="gender" class="form-check-label" for="options-gender-f">Female</label>';
 $sidebar .= '</div>';
 $sidebar .= '</div>';

 $sidebar .= '</div>';

 echo $sidebar;

 $content = '<div class="page-content">';
 $content .= 'Content';
 $content .= '</div>';

 echo $content;
?>
