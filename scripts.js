$(document).ready(function(){
  $('#nav-home').on("click", function(){
    // $('#nav-sub').html(
    //   "<li><a href='#'>Announcements</a></li> \
    //   <li><a href='#'>About</a></li>"
    // );
    // $('#nav-home').css("background-color","green");
    // $('#nav-home a').css("color","white");
    // $('#nav-students').css("background-color","white");
    // $('#nav-students a').css("color","black");
    // $('#nav-reports').css("background-color","white");
    // $('#nav-reports a').css("color","black");

  });

  $('#nav-students').on("click", function(){
    // $('#nav-sub').html(
    //   "<li><a href='#'>Student Info</a></li> \
    //   <li><a href='#'>Add Student</a></li>"
    // );
    // $('#nav-home').css("background-color","white");
    // $('#nav-home a').css("color","black");
    // $('#nav-students').css("background-color","green");
    // $('#nav-students a').css("color","white");
    // $('#nav-reports').css("background-color","white");
    // $('#nav-reports a').css("color","black");

  });

  $('#nav-reports').on("click", function(){
    // $('#nav-sub').html(
    //   "<li><a href='#'>Attendance</a></li>"
    // );
    // $('#nav-home').css("background-color","white");
    // $('#nav-home a').css("color","black");
    // $('#nav-students').css("background-color","white");
    // $('#nav-students a').css("color","black");
    // $('#nav-reports').css("background-color","green");
    // $('#nav-reports a').css("color","white");

  });
  // student index.php
  // Page Specific scripts
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
        url: 'modules/profile.php',
        data: { studentID: studentID },
        success: function(data){
          $('#profileResult').css("display", "block");
          $('#frameInner').html(data);
        }
      })
    }
  })

  // reports index
  $('#reports-category-bystudent').on("click", function(){
    console.log("[+] DEBUG: Reports -> Attendance -> By Student");
  })
  $('#reports-category-bygrade').on("click", function(){
    console.log("[+] DEBUG: Reports -> Attendance -> By Grade");
  })
  $('#reports-category-bysection').on("click", function(){
    console.log("[+] DEBUG: Reports -> Attendance -> By Section");
  })

})
