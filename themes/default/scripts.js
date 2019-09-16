/*
DOM Manipulation & Events
JSON
Fetch API
ES6 + (Arrow functions, promises, async / wait, desctructuring)
*/
$(document).ready(function() {
  var html = $("html");
  loadScripts(html);
});

function loadScripts(html) {
  e = html.find("[data-script]")
  var n = []
  e.each( function() {
    var e = $(this),
        t = e.attr("data-script").split(" "); // Why split ?

    $.each(t, function(t, i){
      // what is this?
      "function" == typeof window[i] && (window[i](e), n.push({
        Element: e,
        Script: i
      })), e.removeAttr("data-script")
    })
  });
}


function gradeLevelFilter() {
  var a = $('#filter-glevel');
  a.change( function() {
    $(this).each( function() {
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
  var s = $('#searchByName');

  var btn = $('#search');

  btn.on("click", function(){
    if(g.val() != "" && v.val() != "" && s.val() == "") {
      t.find("tbody").html("")
      g.each( function(){
        var ylevel = $(this).val()
        $.ajax({
          type: "post",
          url: '/Modules/Student/ListAllStudents/',
          data: { ylevel: ylevel },
          success: function(data) {
            var obj = JSON.parse(data);
            for(var j = 0; j < obj.length; j++) {
              var id = obj[j].idnumber
              var n = obj[j].sname
              t.find("tbody").append("<tr data-idnumber=\""+id+"\" data-name=\""+n+"\"><td>"+id+"</td><td>"+n+"</td><td><a href=\"#View\">View</a> | <a href=\"#Edit\">Edit</a> | <a href=\"#Print\">Print</a></td></tr>")
            }

            var table = $("#studentTable");
            var a = table.children("tbody")
            var b = a.children("tr")
            var c = b.children("td:last-child")
            c.find("a[href=\"#View\"]").click( function(e){
              e.preventDefault();
              id = $(this).closest("tr").data("idnumber")
              name = $(this).closest("tr").data("name")
              // Checks if "11" or "12" in select
              var isKto12 = 0;

              if(g.val().indexOf("11") !== -1) {
                isKto12 = 1
              }
              else if (g.val().indexOf("11") !== -1) {
                isKto12 = 1
              }
              // create table
              $.ajax({
                // url: "http://localhost/attendance/views/studentInfo",
                url: "/Modules/Student/AttendanceSheet/",
                type: "post",
                data: {
                  idnumber: id,
                  action: "view",
                  viewType: v.val(),
                  Kto12: isKto12
                },
                success: function(e) {
                  modalContainer(e, name);

                  // Fill student table with attendance
                  if( v.val() == "DTR"){
                    $.ajax({
                      // url: "http://localhost/attendance/views/studentInfo",
                      url: "/Modules/Student/AttendanceSheet/",
                      type: "post",
                      data: {
                        idnumber: id,
                        action: "update",
                        viewType: v.val()
                      },
                      success: function(data) {
                        var modalInfoContent = $("#modalInfoContent");
                        var table = modalInfoContent.find("table#attendanceSheet");
                        // var table = $("#attendanceSheet");
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
                      url: "/Modules/Student/AttendanceSheet/",
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
    if(s.val() != "") {
      // We should reset the table if theres a result
      t.find("tbody").html("");

      $.ajax({
        type: "post",
        url: "/Modules/Student/ListAllStudents/",
        data: { searchByStudent: s.val() },
        success: function(data) {
          if(data) {
            obj = JSON.parse(data)
            if(obj.length == 1) {
                var id = obj[0].idnumber;
                var n = obj[0].sname;
                t.find("tbody").append("<tr data-idnumber=\""+id+"\" data-name=\""+n+"\"><td>"+id+"</td><td>"+n+"</td><td><a href=\"#View\">View</a> | <a href=\"#Edit\">Edit</a> | <a href=\"#Print\">Print</a></td></tr>")
              }

            if(obj.length > 1) {
              for(var i=0; i<obj.length; i++) {
                var id = obj[i].idnumber;
                var n = obj[i].sname;
                t.find("tbody").append("<tr data-idnumber=\""+id+"\" data-name=\""+n+"\"><td>"+id+"</td><td>"+n+"</td><td><a href=\"#View\">View</a> | <a href=\"#Edit\">Edit</a> | <a href=\"#Print\">Print</a></td></tr>")
              }
            }
            var table = $("#studentTable");
            var a = table.children("tbody")
            var b = a.children("tr")
            var c = b.children("td:last-child")
            c.find("a[href='#View']").click( function(e){
              e.preventDefault();
              id = $(this).closest("tr").data("idnumber")
              name = $(this).closest("tr").data("name")
              // Checks if "11" or "12" in select
              var isKto12 = 0;

              if(g.val().indexOf("11") !== -1) {
                isKto12 = 1
              }
              else if (g.val().indexOf("11") !== -1) {
                isKto12 = 1
              }
              // create table
              $.ajax({
                // url: "http://localhost/attendance/views/studentInfo",
                url: "/Modules/Student/AttendanceSheet/",
                type: "post",
                data: {
                  idnumber: id,
                  action: "view",
                  viewType: v.val(),
                  Kto12: isKto12
                },
                success: function(e) {
                  modalContainer(e, name);

                  // Fill student table with attendance
                  if( v.val() == "DTR"){
                    $.ajax({
                      // url: "http://localhost/attendance/views/studentInfo",
                      url: "/Modules/Student/AttendanceSheet/",
                      type: "post",
                      data: {
                        idnumber: id,
                        action: "update",
                        viewType: v.val()
                      },
                      success: function(data) {
                        var modalInfoContent = $("#modalInfoContent");
                        var table = modalInfoContent.find("table#attendanceSheet");
                        // var table = $("#attendanceSheet");
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
                      url: "/Modules/Student/AttendanceSheet/",
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
                      computetotal()
                      }
                    })
                  }
                }
              })
            })
          }
        }
      })

    }
  })
}

function computetotal() {
  var t = $("#attendanceSheet");
  var th = t.children("thead")
  var tb = t.children("tbody")
  var labels = new Array("present", "absent", "tardy")
  th.each(function(){

    var year = $(this).children("tr").children("th:first").html()
    for(label of labels){
      if(label == "present"){
        var total = 0
        tb.children("tr").children("td[data-label='"+year+"-"+label+"']").each(function(){
          var v = $(this).html()
          total += parseInt(v)
        })
        tb.children("tr[rel='"+year+"-"+label+"']").children("td:last-child").html(total)
      }
    }
  })
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

function modalContainer(e, name) {
  // var student = JSON.parse(e);
  // var timeRecords = timeRecordsTable(student.time_records);
  var c = "<div class='modalContainer'>" +
            "<div class='modalInfoContainer'>" + // container start
              "<div class='modalInfoHeader'>" +
              "<div class='modalInfoTitle'>Attendance Information of " + name + "</div>" +
              "<input class='btn btn-primary modalClose' type='button' value='Close'>" +
              "<input class='btn btn-success modalPrint' type='button' value='Print'>" +
              "<input class='btn btn-success modalPDF' type='button' value='PDF'>" +
              "</div>" +

              "<div id='modalInfoContent'>" +
              "<div id='handler'></div>" +
              e +
              "</div>" +

              // "<div class='modalInfoFooter'>" +
              // "<input class='btn btn-primary modalClose' type='button' value='Close'>" +
              // "</div>" +
            "</div>" + // container end
          "</div>";
  $("body").append(c);
  $(".modalContainer").css("display", "block");

  toPDF();
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

function toPDF() {
  // var source = $(".modalInfoContent").html();
  var s = $(".modalPDF");

  s.on("click", function() {
    var doc = new jsPDF();

    res = doc.autoTableHtmlToJson(document.getElementById("attendanceSheet"))
    // res = doc.autoTableHtmlToJson({
    //   html: "#attendanceSheet",
    //   styles: {
    //     width: "100%"
    //   },
    // })
    // var string = doc.output('datauristring');
    // var iframe = "<iframe width='100%' height='100%' src='" + string + "'></iframe>"
    // var x = window.open();
    // x.document.open();
    // x.document.write(iframe);
    // x.document.close();
  });
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


function schoolYear() {
  var d = $(".schoolYear")
  var t = $("#term");

  // Toggle display options
  d.on("click", function(e){
    if(e.target !== e.currentTarget) return;
    var o = $(".schoolYearOptions")
    if(o.css("display") == "none"){
      o.css("display", "inline-block")
    }
    else if(o.css("display") == "block"){
      o.css("display", "none")
    }

  })

  $(".setButton").on("click", function(e){
    $.ajax({
      type: "post",
      url: "/Modules/SchoolYear/Set/",
      data: { term: t.val() },
      success: function(data) {
        location.reload()
      }
    })
  })
}

function grade_attendance() {
  var ga = $(".page-content").find(".ga-title");
  ga.each( function() {
    $(this).on("click", function() {
      // if($(this).find(".ga-content").css("display") == "none")
      var gac = $(this).find(".ga-content");
      if(gac.css("display") == "none") {
        gac.css("display", "inline-block");
      }else {
        gac.css("display", "none");
      }
    })
  })
}