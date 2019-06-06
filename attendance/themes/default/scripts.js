
$(document).ready(function(){
  url_base = window.location.origin+"/";
  function systemBootstrap() {
    var html = $("html");

    loadScripts(html);
  }
  systemBootstrap();

  var acc = document.getElementsByClassName("accordion");
  var i;

  for(i = 0; i < acc.length; i++ ) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");

      var panel = this.nextElementSibling;
      if(panel.style.display === "block") {
        panel.style.display = "none";
      } else {
        panel.style.display = "block";
      }
    })
  }

})

// Functions outside load.
function loadScripts(e) {
  "string" == typeof e && (e = $(e));
  var t = e.find("[data-script]");
  if (!e.length || !t.length) return !0;
  var n = [];
  t.each(function() {
      var e = $(this),
          t = e.attr("data-script").split(" ");
      $.each(t, function(t, i) {
          "function" == typeof window[i] && (window[i](e), n.push({
              Element: e,
              Script: i
          }))
      }), e.removeAttr("data-script")
  })
}

function gradeLevelFilter() {
  var a = $('#filter-glevel');
  a.change( function() {
    $(this).each( function() {
      console.log($(this).val());
    })
  })
}

function studentTable() {
  var pc = $(".page-content");
  var t = $(".studentTable");
  t.find("thead").css({
    "background-color": "green",
    "color": "white",
  })
  var g = $('#gradeSelect');
  g.change( function() {
    t.find("tbody").html("")
    g.each( function(){
      var ccode = $(this).val()
      $.ajax({
        type: "post",
        url: 'http://localhost/models/loadStudentTable/',
        data: { ccode: ccode },
        success: function(data) {
          var obj = JSON.parse(data);

          for(var j = 0; j < obj.length; j++) {
            var id = obj[j].idnumber
            var n = obj[j].sname
            t.find("tbody").append("<tr><td>"+id+"</td><td>"+n+"</td><td><a href='"+url_base+"views/reports/student/?idnumber="+id+"'>View</a> | <a href='#View'>Edit</a> | <a href='#View'>Print</a></td></tr>")
          }
        }
      })
    })
  })


}

function loadStudentTable() {
  var table = $("#studentTable");
  var a = (table.children("thead"), table.children("tbody"));
  var b = a.children("tr")
  var c = b.children("td:last-child")

  c.find('a[href="#View"]').click( function(e){
    id = $(this).closest("tr").data("idnumber")

    $.ajax({
      // url: "http://localhost/attendance/views/studentInfo",
      url: "http://localhost/views/studentInfo/",
      type: "post",
      data: {
        requestType: "requestInfo",
        idnumber: id
      },
      success: function(e) {
        modalContainer(e);
      },
      error: function() {

      }
    })
  })
}

function modalContainer(e) {
  // console.log(e)
  // var student = JSON.parse(e);
  // var timeRecords = timeRecordsTable(student.time_records);
  var c = "<div class='modalContainer'>" +
            "<div class='modalInfoContainer'>" + // container start
              "<div class='modalInfoHeader'>" +
              "<div class='modalInfoTitle'>Attendance Information of: </div>" +
              "<input class='btn btn-primary modalClose' type='button' value='Close'>" +
              "</div>" +

              "<div class='modalInfoContent'>" +
              e +
              "</div>" +

              // "<div class='modalInfoFooter'>" +
              // "<input class='btn btn-primary modalClose' type='button' value='Close'>" +
              // "</div>" +
            "</div>" + // container end
          "</div>";
  $("body").append(c);
  $(".modalContainer").css("display", "block");

  closeModalContainer();
}

function closeModalContainer() {
  var s = $(".modalClose");
  s.click( function() {
    $(".modalContainer").remove();
    $(".modalContainer").css("display", "none");
  })
}

function  attendanceTable(time_records) {

  const monthNames = ["January", "February", "March",
                      "April", "May", "June", "July",
                      "August", "September", "October",
                      "November", "December"];
  var table = ""
  var monthsToDisplay = []

  for(var i = 0; i < time_records.length; i++) {
    var month = new Date(time_records[i].time_recorded).getMonth()
    if(monthsToDisplay.indexOf(month) == -1){
      monthsToDisplay.push(month)
    }
  }

  var t = function(m) {
    return "<table class='table table-bordered' style='border: 1px solid black;'><thead><tr><th colspan=2>" + monthNames[m-1] + "</th></tr></thead><tbody><tr><td>In</td><td>Out</td></tr></tbody></table>"
  }

  if(monthsToDisplay.length > 0) {
    for(var i = 0; i < monthsToDisplay.length; i++) {
      table += t(monthsToDisplay[i])
    }
  }
  return table;

}
