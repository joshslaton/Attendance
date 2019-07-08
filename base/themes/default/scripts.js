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
  var page = ""

  printbtn.on("click", function(e){
    e.preventDefault();

    $.ajax({
      type: "post",
      url: "https://kiosk.lorma.edu/Modules/requests",
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
            // console.log(inputIDNumber.val())
            PrintElem(data.file)
          }
        })
      }
    })
  })
}

// Download the file temporarily
// access it locally and print
function PrintElem(link){
  win = window.open(link, "PRINT", "width=800,height=600");
  win.focus()
  setTimeout( function() {
    win.print()
  }, 2000)
}
// function PrintElem(file){
// 	console.log(file)
//   const proxyurl = "https://cors-anywhere.herokuapp.com/";
//   const url = file
//   fetch(proxyurl + url)
//   .then(contents => response.txt())
//   .then(contents => console.log(contents))
//   .catch(() => console.log("Can't access " + url +" response. Blocked by browser?"))
//
//   // var mywindow = window.open(file, 'PRINT', 'height=400,width=600');
//
//   console.log("Done");
// }
