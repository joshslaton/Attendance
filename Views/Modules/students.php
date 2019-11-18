<?php
function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    });
 
    return $array;
}

if($_POST["action"] == "ShowAllStudents") {
    echo json_encode(utf8_converter($students));
}
if($_POST["action"] == "addStudent") {
    $error = "";
    $response = "";

    
    if(!is_null($_POST["contact"])) {
        if(!isMobileNumber($_POST["contact"])) {
            $error .= "Not a valid mobile number.";
        }else{
            if(!validPrefixes($_POST["contact"])) {
                $error .= "Number contains invalid prefix.";
            }else {
                $response = "Add user success!";
            }
        }
    }


    $message = array(
        "error" => $error,
        "response" => $response
    );
    echo json_encode($message);
}


// Contact Number Checking
function isMobileNumber($number) {
    return strlen($number) == 10 || strlen($number) == 11;
}

function isNumberOnly($number) {

}

function validPrefixes($number) {
    // Convert this to array
    $validPrefixes = "0813, 0928, 0966, 0817, 0929, " .
                "0967, 0904, 0930, 0970, 0905, " .
                "0931, 0973, 0906, 0932, 0975, " .
                "0907, 0933, 0976, 0908, 0934, " .
                "0977, 0909, 0935, 0978, 0910, " .
                "0936, 0979, 0911, 0937, 0981, " .
                "0912, 0938, 0989, 0913, 0939, " .
                "0994, 0914, 0941, 0995, 0915, " .
                "0942, 0997, 0916, 0943, 0998, " . 
                "0917, 0944, 0999, 0918, 0945, " .
                "09173, 0919, 0946, 09175, 0920, ".
                "0947, 09176, 0921, 0948, 09178, " .
                "0922, 0949, 09253, 0923, 0950, " .
                "09255, 0924, 0955, 09256, 0925, " .
                "0956, 09257, 0926, 0961,09258, 0927, 0965";
    $validPrefixesArray = array();

    foreach(explode(",", $validPrefixes) as $vP) {
        $vP = str_replace(" ", "", $vP);
        array_push($validPrefixesArray, $vP);
    }

    foreach($validPrefixesArray as $vP) {
        if(strlen($number) == 10) {
            if(substr($vP, 0, 1) == "0") {
                $vP = ltrim($vP, "0");
                if(substr($number, 0, strlen($vP)) == $vP) {
                    return True;
                }
            }
        }elseif(strlen($number) == 11) {
            if(substr($number, 0, strlen($vP)) == $vP) {
                return True;
            }
        }
    }
    return false;
}