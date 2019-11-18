<div class="container">
    <div class="center">
        <!-- <table class="table form_addUser" data-script="addUser"> -->
         <?php
            // $r = explode($response);
            if(isset($response)) {
                if($response["status"] == "Success") {
                    echo "<div class=\"status status-success\" data-script=\"statusNotif\">".$response["message"]."</div>";
                }
                if($response["status"] == "Failed") {
                    echo "<div class=\"status status-failed\" data-script=\"statusNotif\">".$response["message"]."</div>";
                }
            }
         ?>
        <table class="table form_addUser tableForm">
            <thead>
            <tr><th colspan=2>Add User</th></tr>
            </thead>
            <tbody>
            <form method='post' action='#'>
                <tr><td><label>ID Number</label></td><td><input type="text" class="form-control" id="input_idNumber" name="idnumber"></td></tr>
                <tr><td><label>First Name</label></td><td><input type="text" class="form-control" id="input_firstName" name="fname"></td></tr>
                <tr><td><label>Middle Name</label></td><td><input type="text" class="form-control" id="input_middleName" name="mname"></td></tr>
                <tr><td><label>Last Name</label></td><td><input type="text" class="form-control" id="input_lastName" name="lname"></td></tr>
                <tr><td><label>Contact</label></td><td><input type="text" class="form-control" id="input_contactNumber" name="contact"></td></tr>
                <tr>
                    <td>Grade Level</td>
                    <td>
                        <select class="form-control" id="input_yearLevel"  name="ylevel">
                            <?php
                            foreach($yearLevels as $yl) {
                                echo "<option value=\"".$yl["ylevel"]."\">".$yl["ylevel"]."</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                        <input type="submit" class="btn btn-primary" id="btn_formSubmit" value="Submit">
                        <input type="button" class="btn btn-success" id="btn_formReset" value="Reset Form">
                    </td>
                </tr>
            </form>
            </tbody>
        </table>
    </div>
</div>
