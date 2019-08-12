<?php
namespace Core;
include("/vagrant/html/base/controllers/Modules/SMS.php");

if(!is_null($_GET["action"]) && !is_null($_GET["idnumber"]) && !is_null($_GET["dir"])) {
    if($_GET["action"] == "s")
        SMS::Sender();
    
    if($_GET["action"] == "r")
        SMS::Record();
}
// print_r(get_included_files());