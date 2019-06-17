
$(document).ready(function(){
  url_base = window.location.origin+"/";
  function systemBootstrap() {
    var html = $("html");

    loadScripts(html);
  }
  systemBootstrap();

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

function getListOfStudents() {
  var pc = $(".page-content");
  var t = $(".studentTable");
  t.find("thead").css({
    "background-color": "green",
    "color": "white",
  })
  var g = $('#gradeSelect');
  var v = $('#viewSelect');
  var btn = $('#search');

  btn.on("click", function(){
    if(g.val() != "" && v.val() != "") {
      t.find("tbody").html("")
      g.each( function(){
        var ccode = $(this).val()
        $.ajax({
          type: "post",
          url: url_base+'Requests/Student/ListAllStudentsByGrade/',
          // url: url_base+'/models/loadStudentTable/',
          data: { ccode: ccode },
          success: function(data) {
            var obj = JSON.parse(data);
            for(var j = 0; j < obj.length; j++) {
              var id = obj[j].idnumber
              var n = obj[j].sname
              // t.find("tbody").append("<tr data-idnumber='"+id+"'><td>"+id+"</td><td>"+n+"</td><td><a href='"+url_base+"views/reports/student/?idnumber="+id+"'>View</a> | <a href='#Edit'>Edit</a> | <a href='#Print'>Print</a></td></tr>")
              t.find("tbody").append("<tr data-idnumber='"+id+"'><td>"+id+"</td><td>"+n+"</td><td><a href='#View'>View</a> | <a href='#Edit'>Edit</a> | <a href='#Print'>Print</a></td></tr>")
            }

            var table = $("#studentTable");
            var a = table.children("tbody")
            var b = a.children("tr")
            var c = b.children("td:last-child")
            c.find("a[href='#View']").click( function(e){
              e.preventDefault();
              id = $(this).closest("tr").data("idnumber")
              // create table
              $.ajax({
                // url: "http://localhost/attendance/views/studentInfo",
                url: url_base+"Requests/Student/AttendanceSheet/",
                type: "post",
                data: {
                  idnumber: id,
                  action: "view",
                  viewType: v.val()
                },
                success: function(e) {
                  modalContainer(e, id);

                  // Fill student table with attendance
                  if( v.val() == "DTR"){
                    $.ajax({
                      // url: "http://localhost/attendance/views/studentInfo",
                      url: url_base+"Requests/Student/AttendanceSheet/",
                      type: "post",
                      data: {
                        idnumber: id,
                        action: "update",
                        viewType: v.val()
                      },
                      success: function(data) {
                        var table = $("#attendanceSheet");
                        obj = JSON.parse(data)
                        for(var i = 0; i < obj.length; i++) {
                          var sDate = obj[i]["time"].split(" ")[0]
                          var sTime = obj[i]["time"].split(" ")[1]
                          var sTime = sTime.split(":")
                          if(obj[i].gate == "in"){
                            table.find("td[rel='"+sDate+"']").find("#in").append(sTime[0]+":"+sTime[1]+"<br>")
                          }
                          if(obj[i].gate == "out"){
                            table.find("td[rel='"+sDate+"']").find("#out").append(sTime[0]+":"+sTime[1]+"<br>")
                          }
                        }
                      }
                    })
                  }
                  if( v.val() == "Classcard"){
                    $.ajax({
                      // url: "http://localhost/attendance/views/studentInfo",
                      url: url_base+"Requests/Student/AttendanceSheet/",
                      type: "post",
                      data: {
                        idnumber: id,
                        action: "update",
                        viewType: v.val()
                      },
                      success: function(data) {
                        var table = $("#attendanceSheet");
                        obj = JSON.parse(data)

                        Object.entries(obj).forEach(entry1 => {
                        let label = entry1[0]
                        let years = entry1[1]

                        Object.entries(years).forEach(entry2 => {
                          let year = entry2[0]
                          let months = entry2[1]

                          Object.entries(months).forEach(entry3 => {
                            let monthName = entry3[0]
                            let numberOfDaysPresent = entry3[1]

                            // TODO: total, values according to proper label.
                            if(label == "present") {
                              table.find("td[rel='"+year+"-"+monthName+" "+label+"']").html(numberOfDaysPresent)
                            }
                          })
                        })
                      }) // Object.entries
                      // Compute total
                      computetotal()
                      }
                    })
                  }
                }
              })
            })
          }
        })
      })
    }
  })
}

function computetotal() {
  var table = $("#attendanceSheet");
  var tb = table.children("tbody")
  var l = tb.children("tr").find("td[id='present']")
  var total = 0
  l.each(function(data){
    total += parseInt($(this).html())
  })
  table.find("td[rel='present total']").html(total)
}

function loadStudentTable() {
  var table = $("#studentTable");
  var a = (table.children("thead"), table.children("tbody"))
  var b = a.children("tr")
  var c = b.children("td:last-child")

  c.find('a[href="#View"]').click( function(e){
    id = $(this).closest("tr").data("idnumber")

    $.ajax({
      // url: "http://localhost/attendance/views/studentInfo",
      url: url_base+"views/studentInfo/",
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
              "<div class='modalInfoTitle'>Attendance Information</div>" +
              "<input class='btn btn-primary modalClose' type='button' value='Close'>" +
              "<input class='btn btn-success modalPrint' type='button' value='Print'>" +
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

  printModalContents();
  closeModalContainer();
}

function printModalContents() {
  var s = $(".modalPrint");
  s.click( function() {
    var c = $(".modalInfoContent").html();
    var p = window.open('', 'Print', 'width=600, height=600');
    var htmlToPrint = '' +
    '<style type="text/css">' +
    `
    @font-face {
        font-family: 'Montserrat';
        src: url('fonts/Montserrat-Light.otf');
        src: url('fonts/Montserrat-Light.otf') format('opentype');
    }

    * {
      font-family: 'Arial', sans-serif;
    }

    table {
      table-layout: fixed !important;
    }

    #attendanceYearLabel {
      background-color: green;
      color: white;
      font-weight: bold;
    }
    td, th{
      /* width: auto !important; */
      white-space: nowrap;
      width: 1px;
      font-size: 0.7em;
    }

    .row {
      display: flex;
      margin: 2px;
    }

    .column {
      flex: 50%;
      margin: 2px;
    }
    .column-header {
      font-size: .5em;
      font-weight: bold;
      border-bottom: 1px solid #ccc;
      color: #ccc;
    }` +
    '</style>';

    p.document.write("<html>");
    p.document.write("<head>");
    p.document.write("<title>Print Document</title>");
    p.document.write(htmlToPrint);
    // p.document.write("<link href='http://localhost/themes/default/style.css' rel='stylesheet'>");
    p.document.write("</head>");
    p.document.write("<body>");
    p.document.write(c);
    p.document.write("</body>");
    p.document.write("</html>");
    // p.document.close();
    p.focus();

    p.print();
    p.close();

    return true;
  })
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

function fillAttendance() {
  var idnumber = "3800007";
  var pc = $(".page-content");

  $.ajax({
    type: 'post',
    url: url_base+'views/studentInfo/',
    data: { idnumber: idnumber, requestType: "requestAttendance" },
    success: function(data) {
      var obj = JSON.parse(data)
      console.log(obj);
      for(var i = 0; i < obj.length; i ++) {
        var d = obj[i].time.split(" ")[0];
        var t = obj[i].time.split(" ")[1];
        pc.find("tr[rel='"+d+"']").css({
        "background-color": "rgb(0, 255, 0, 0.2)"
        })
        pc.find("tr[rel='"+d+"']").children("td[rel='time']").html(t)
        pc.find("tr[rel='"+d+"']").children("td[rel='remarks']").html("Any notes goes here")
      }
    }

  })
}

function test() {
  console.log("test");
}
