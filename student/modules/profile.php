<?php
include_once($_SERVER["DOCUMENT_ROOT"].'includes/DB.inc.php');
$db = new db();

?>
<?php
if(isset($_POST["studentID"]) && $_POST["studentID"] != ""){
$results = $db->query("SELECT *, concat(contact1,' ', contact2,' ', contact3) as contact FROM preschool WHERE idnumber=".$_POST["studentID"])->fetchAll();
foreach($results as $result){
?>
<table border=1 cellpadding=10>
  <tbody>
    <tr>
      <td colspan="3">
        <span>
          <form action='http://localhost/SMS/attendance/reports/' method='get'>
            <input type='submit' class='btn btn-primary' value='Attendance'>
            <input type='hidden' name='idnumber' value='<?php echo $_POST['studentID'] ?>'>
            <input type='hidden' name='category' value='byStudent'>
          </form>
        </span>
      </td>
    </tr>
    <tr>
      <td rowspan='7'>
        <img src='./res/avatar.png' id='profile-picture'>
        <td><span class='profileLabel'>Student ID:</span></td>
        <td><span id='profileStudentID-value'><?php echo $result["idnumber"]; ?></span></td>
      </td>
      <tr>
        <td><span class='profileLabel'>First Name:</span></td>
        <td><span id='profileFname-value'><?php echo $result["name"]; ?></span></td>
      </tr>
      <tr>
        <td><span class='profileLabel'>Middle Name:</span></td>
        <td><span id='profileMname-value'></span></td>
      </tr>
      <tr>
        <td><span class='profileLabel'>Last Name:</span></td>
        <td><span id='profileLname-value'></span></td>
      </tr>
      <tr>
        <td><span class='profileLabel'>Birthdate:</span></td>
        <td><span id='profileBdate-value'></span></td>
      </tr>
      <tr>
        <td><span class='profileLabel'>Sex:</span></td>
        <td><span id='profileSex-value'><?php echo $result["gender"]; ?></span></td>
      </tr>
      <tr>
        <td><span class='profileLabel'>Contact #:</span></td>
        <td><span id='profileSex-value'><?php  ?></span></td>
      </tr>
    </tr>
    <tr>
      <td colspan='3'>
        <span class='profileLabel'>Parent or Guardian:</span>
      </td>
    </tr>
    <tr>
      <td colspan='3'>
        <span class='profileLabel'>Name:</span>
        <span id='profileGuardian-value'></span>
      </td>
    </tr>
    <tr>
      <td colspan='3'>
        <span class='profileLabel'>Contact:</span>
        <span id='profileGuardianContact-value'><?php echo $result["contact"]; ?></span>
      </td>
    </tr>
    <tr>
      <td colspan='3'>
        <span class='profileLabel'>Address 1:</span>
        <span id='profileGuardianAddress1-value'></span>
      </td>
    </tr>
    <tr>
      <td colspan='3'>
        <span class='profileLabel'>Address 2:</span>
        <span id='profileGuardianAddress2-value'></span>
      </td>
    </tr>
    <tr>
      <td colspan='3'>
        <span class='profileLabel'>Address 3:</span>
        <span id='profileGuardianAddress3-value'></span>
      </td>
    </tr>
    </tr>
  </tbody>
</table>
<?php
} // foreach End of Block
} // if End of block
?>
