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
      console.log(typeof window[i])
      "function" == typeof window[i] && (window[i](e), n.push({
        Element: e,
        Script: i
      })), e.removeAttr("data-script")
    })
  });
}

function test(){
  console.log("[+] Function: test()")
}
