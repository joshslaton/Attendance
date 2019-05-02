$(document).ready(function(){

  var genderSelected = [];
  var sectionSelected = [];

  $('.sidebar-options').on("change", function(){

    $('.sidebar-options:checked[name=gender]').each( function(){
      genderSelected.push($(this).val());
    });

    $('.sidebar-options:checked[name=section]').each( function(){
      sectionSelected.push($(this).val());
    });

    if(genderSelected.length > 0 && sectionSelected.length > 0){
      var gender;
      var section;

      gender = genderSelected.join(",");
      section = sectionSelected.join(",");

      $.ajax({
        type: 'get',
        url: 'http://localhost/reports/attendance/section/functions.php',
        data: {gender: gender, section: section},
        success: function(data){
          $('.content-wrapper').html(data);
        }
      })
    }else{
      $('.content-wrapper').html("");
    }
    genderSelected.length = 0;
    sectionSelected.length = 0;
  });

})
