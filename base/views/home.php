<?php

/*
Structure:
$_POST|$_GET = array(
    key => <public_key>,
    action => <action>,
    field => <field_key>,
    <field_key> => <field_value>,
    sec => sha1(<field_value> . <private_key>),
    [<vars> => <varvalue>, ..]
);

Your keys:
    private: RtplJU6P6iPZjpkp0cn3MCvL2uDwMapA
    public: n5YBGMtJjT4JHlt7

Sample:
  https://lcaccess.lorma.edu/api/?
  key=n5YBGMtJjT4JHlt7&
  action=assessmentPDF&
  field=studid&
  studid=1700066&
  sec=fdf4d78489b8ffc94ebba35f09a91a437ede8687

  https://lcaccess.lorma.edu/api/? key=n5YBGMtJjT4JHlt7&action=assessmentPDF& field=studid&studid=1700066&sec=fdf4d78489b8ffc94ebba35f09a91a437ede8687
create iframe
hide
print functions
*/

// TODO: Sanitize input
$html = "";
$html .= "";
$html .= "<div class=\"form-group primaryField\">";
$html .= "<label for=\"inputIDNumber\">Student ID:</label>";
  $html .= "<input id=\"inputIDNumber\" class=\"form-control\" type=\"text\" value=\"1700066\">";
  $html .= "<input class=\"btn btn-primary form-control\" type=\"button\" value=\"Print\" data-script=\"printAssesment\">";
$html .= "</div>";

echo $html;

// $pdfFile = "https://lcaccess.lorma.edu/files/2019/07/08/Assess-1700066s19070808pm.pdf";
// $data = file_get_contents($pdfFile);
// header("Content-type: application/pdf");
// header("Content-disposition: attachment;filename=temp.pdf");
// echo $data;

// https://lcaccess.lorma.edu/files/2019/07/08/Assess-1700066s19070808pm.pdf
