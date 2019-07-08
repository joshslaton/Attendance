<?php
if($_POST["idnumber"] != NULL) {
  $p = "RtplJU6P6iPZjpkp0cn3MCvL2uDwMapA";
  $id = $_POST["idnumber"];
  $sec = sha1($id.$p);

  echo $sec;
}

if($_POST["requestType"] != NULL) {
  $rt = $_POST["requestType"];

  if($rt == "print") {
    $pdfFile = $_POST["link"]; // need sanity check
    $data = file_get_contents($pdfFile);
    header("Content-type: application/pdf");
    header("Content-disposition: attachment;filename=temp.pdf");
    // echo $data;
    echo $pdfFile;
  }
}

// https://lcaccess.lorma.edu/files/2019/07/08/Assess-1700066s19070808pm.pdf
// https://lcaccess.lorma.edu/files/2019/07/08/Assess-1700066s19070809pm.pdf
// https://lcaccess.lorma.edu/api/?key=n5YBGMtJjT4JHlt7&action=assessmentPDF&field=studid&studid=1700066&sec=fdf4d78489b8ffc94ebba35f09a91a437ede8687
