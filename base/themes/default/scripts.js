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

function printAssesment(){
  var printbtn = $("input[Value=\"Print\"]")
  var inputIDNumber = $("input#inputIDNumber")
  var pk = "n5YBGMtJjT4JHlt7"

  printbtn.on("click", function(e){
    e.preventDefault();
    var link = ""
    $.ajax({
      type: "post",
      url: "http://localhost/Modules/requests",
      data: { idnumber: inputIDNumber.val() },
      success: function(k) {
        $.ajax({
          type: "post",
          url: "https://lcaccess.lorma.edu/api/",
          data: {
            key: pk,
            action: "assessmentPDF",
            field: "studid",
            studid: inputIDNumber.val(),
            sec: k
          },
          success: function(data) {
            link = data.file
          },
          complete: function() {
            
          }
        })
      }
    })
  })
}
