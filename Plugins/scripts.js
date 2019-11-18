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

function showAllUsers() {
  $.ajax({
    url : "/modules/students/",
    type: "post",
    data: { "action": "ShowAllStudents" },
    success: function(data) {
      obj = JSON.parse(data);
      var itemsPerPage = 10;
      var body = $("body");
      var t = $(".userTable"),
      thead = t.find("thead"),
      tbody = t.find("tbody");
      var html = "";
      if(obj.length > 0) {
        // var page_length = Math.floor(obj.length / itemsPerPage);

        // t.before("<ul class=\"pagination\"></ul>");
        // for(var i = 1; i <= page_length; i++) {
        //     if(i == 1)
        //         $(".pagination").append("<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">"+i+"</a></li>");
        //     else
        //         $(".pagination").append("<li class=\"page-item\"><a class=\"page-link\" href=\"#\">"+i+"</a></li>");
        // }


        for(var i = 0; i < obj.length; i++) {
          html = "<tr>" +
          "<td>"  + obj[i]["idnumber"] + "</td>" +
          "<td><b>"  + obj[i]["lname"] + "<b></td>" +
          "<td>"  + obj[i]["fname"] + "</td>" +
          "<td>"  + obj[i]["mname"] + "</td>" +
          // "<td>"  + obj[i]["contact"] + "</td>" +
          "<td>"  + "" + "</td>" +
          "<td>"  + obj[i]["ylevel"] + "</td>" +
          "<td><a href=\"#\">View Attendance</a> | <a href=\"#\">Edit</a></td>" +
          "<tr>";
          tbody.append(html);
        }
      }
    },
    complete: function() {
      // var pagination = $("ul.pagination");
      // pagination.find("li").on("click", function() {
      //     $(this).each( function() {
      //         console.log($(this).find("a").html());
      //     });
      // })
    }
  })
}

function addUser() {
  var inputs = $("body").find("table").find("input.form-control");
  var btnSubmit = $("#btn_formSubmit"),
  btnReset = $("#btn_formReset");

  btnReset.on("click", function() {
    inputs.val("");
  })

  // Contact number, delimiters(, ; " ")
  $("form").on("submit", function(e) {
    var idnumber = $("#input_idNumber"),
    fname = $("#input_firstName"),
    mname = $("#input_middleName"),
    lname = $("#input_lastName"),
    contact = $("#input_contactNumber"),
    ylevel = $("#input_yearLevel option:selected");

    if(idnumber.val == "" || fname.val() == "" || mname.val() == "" || lname.val() == "" || contact.val() == "") {
      status("error", "One of the fields are empty!");
      e.preventDefault();
    }else {
      $.ajax({
        url : "/user/create/",
        type: "post",
        data: {
          "idnumber": idnumber.val(),
          "fname": fname.val(),
          "mname": mname.val(),
          "lname": lname.val(),
          "contact": contact.val(),
          "ylevel": ylevel.val(),
        },
        success: function(data) {

        }
      })
    }
  })
}

function status(status, message) {
  var s = $(".status");
  if(status == "error") {
    $(".form_addUser").before("<div class=\"status status-failed\">"+message+"</div>");
    setTimeout( function(){
      statusNotif();
    }, 3000);
  }else {
    $(".form_addUser").before("<div class=\"status status-success\">"+message+"</div>");
    setTimeout( function(){
      statusNotif();
    }, 3000);
  }
}

function statusNotif() {
  var s = $(".status");
  if(s.length  > 0) {
    setTimeout( function(){
      s.remove();
    }, 3000);
  }
}

function modifyStudent() {
  var id = "",
  idnumber = "",
  fname = "",
  mname = "",
  lname = "",
  contact = "",
  ylevel = "";
  var t = $("#modifyStudent");

  t.find("input").each(function() {
    hidden = $("input[type=hidden]").val();
    $(this).on("focus", function() {
      if($(this).attr("name") == "idnumber")
        idnumber = $(this).val()
      if($(this).attr("name") == "fname")
        fname = $(this).val()
      if($(this).attr("name") == "mname")
        mname = $(this).val()
      if($(this).attr("name") == "lname")
        lname = $(this).val()
      if($(this).attr("name") == "contact")
        contact = $(this).val()
    })
  })
  t.find("input").each(function() {
    $(this).on("blur", function() {
      // ID Number
      if($(this).attr("name") == "idnumber") {
        if($(this).val() != idnumber && $(this).val() != "") {
          var newVal = $(this).val();
          var r = ajaxRequest("post", "/student/update/", { "id": hidden, "oldVal": idnumber, "newVal": newVal, "column": $(this).attr("name") })
        }
      }
      // First Name
      if($(this).attr("name") == "fname") {
        if($(this).val() != fname && $(this).val() != "") {
          var newVal = $(this).val();
          ajaxRequest("post", "/student/update/", { "id": hidden, "oldVal": fname, "newVal": newVal, "column": $(this).attr("name") })
        }
      }
      // Middle Name
      if($(this).attr("name") == "mname") {
        if($(this).val() != mname && $(this).val() != "") {
          var newVal = $(this).val();
          ajaxRequest("post", "/student/update/", { "id": hidden, "oldVal": mname, "newVal": newVal, "column": $(this).attr("name") })
        }
      }
      // Last Name
      if($(this).attr("name") == "lname") {
        if($(this).val() != lname && $(this).val() != "") {
          var newVal = $(this).val();
          ajaxRequest("post", "/student/update/", { "id": hidden, "oldVal": lname, "newVal": newVal, "column": $(this).attr("name") })
        }
      }
    })
  })
}

function searchStudent() {
  var searchRequest = null;
  var minLength = 2;

  $("#searchStudent").autocomplete({
    source: function(request, response) {
      $.post("/student/search/", { idnumber:request.term }, function(data) {
        response($.map(data, function(item) {
          return {
            // label: item.id,
            label: item.idnumber + " | " +
                    item.lname + " " + item.fname + " " + item.mname + " | " + item.ylevel,
            value: item.idnumber
          }
        }))
      }, "json")
    },
    minLength: minLength,
    dataType: "json",
    // cache: false,
    focus: function(event, ui) {
      return false;
    },
    select: function(event, ui) {
      this.value = ui.item.value;
      return false;
    }
  })
}

function ajaxRequest(type, url, data) {
  $.ajax({
    url: url,
    type: type,
    data: data,
    success: function(d) {
      r = JSON.parse(d);
      r = r.result
      if(r.status == "success") {
        $("#modifyStudent").before("<div class=\"status status-success\">"+r.message+"</div>")
        statusNotif();
      }
      else {
        $("#modifyStudent").before("<div class=\"status status-failed\">"+r.message+"</div>")
        setTimeout( function() {
          location.reload()
        }, 2000)
        statusNotif();
      }
    }
  });
}
