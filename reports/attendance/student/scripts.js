$(document).ready(function(){
  $("#enterStudentID").on("keyup", function(){
    $('#searchResults').css("display", "block");
    if($(this).val().length > 2){
      var val = $(this).val();
      $.ajax({
        type: "post",
        url: "modules/searchStudentID.php",
        data: { studentID: val },
        success: function(data){
          $('#searchResults').html(data);
        }
      })
    }else{
      $('#searchResults').html("");
    }
  })
  $(document).on("click", "#studentInfo", function(){
    var id = $(this).data("id");
    $('#enterStudentID').val(id);
    $('#searchResults').css("display", "None");
  })

  $(document).on("click", "#searchProfile", function(){
    // Hard coded to only accept IDNUMBER
    if($("#enterStudentID").val().length == 7){
      var studentID = $("#enterStudentID").val();
      $.ajax({
        type: 'post',
        url: 'modules/showAttendance.php',
        data: { studentID: studentID },
        success: function(data){
          $('#profileResult').css("display", "block");
          $('#frameInner').html(data);
        }
      })
    }
  })
})
