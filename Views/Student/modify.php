<div id="divStudentSearch" class="center" data-script="searchStudent">
    <form method="post" action="/student/modify/">
        Student Search:
        <input type="text" name="idnumber" id="searchStudent">
        <input type="submit" value="Search and Display">
    </form>
</div>
<?php
if(isset($student) && $student[0] != "") {
    $student = $student[0];
?>
<div class="container">
    <div class="center">
        <table id="modifyStudent" class="table w-auto tableForm" data-script="modifyStudent">
            <thead>
            <tr><th colspan=2>Modify User</th></tr>
            </thead>
            <tbody>
            <form method='post' action='#'>
                <tr>
                    <td><label>ID Number</label></td>
                    <td>
                      <input type="hidden" class="form-control" name="idnumber" value="<?php echo $student["id"]; ?>">
                      <input id="input_idnumber" type="text" class="form-control" name="idnumber" value="<?php echo $student["idnumber"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td><label>First Name</label></td>
                    <td><input id="input_fname" type="text" class="form-control" name="fname" value="<?php echo $student["fname"]; ?>"></td>
                </tr>
                <tr>
                    <td><label>Middle Name</label></td>
                    <td><input id="input_mname" type="text" class="form-control" name="mname" value="<?php echo $student["mname"]; ?>"></td>
                </tr>
                <tr>
                    <td><label>Last Name</label></td>
                    <td><input id="input_lname" type="text" class="form-control" name="lname" value="<?php echo $student["lname"]; ?>"></td>
                </tr>
                <tr>
                    <td><label>Contact</label></td>
                    <td><input id="input_contact" type="text" class="form-control" name="contact" value="<?php echo $student["contact"]; ?>"></td>
                </tr>
                <tr>
                    <td>Grade Level</td>
                    <td>
                        <select class="form-control" id="input_yearLevel"  name="ylevel">
                            <?php
                            $select = "";
                            foreach($yearLevels as $yl) {
                              if($yl["ylevel"] == $student["ylevel"]) {
                                $select .= "<option value=\"".$yl["ylevel"]."\" selected>".$yl["ylevel"]."</option>";
                              }else {
                                $select .= "<option value=\"".$yl["ylevel"]."\">".$yl["ylevel"]."</option>";
                              }
                            }
                            echo $select;
                            ?>
                        </select>
                    </td>
                </tr>
                <!-- <tr>
                    <td colspan=2>
                        <input type="submit" class="btn btn-primary" id="btn_formSubmit" value="Submit">
                        <input type="button" class="btn btn-success" id="btn_formReset" value="Reset Form">
                    </td>
                </tr> -->
            </form>
            </tbody>
        </table>
    </div>
<?php
}
?>
</div>
