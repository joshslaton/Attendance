<div id="pageBody">

  <?php
  $todo = array();
  if($todo){

    $html = "";
    $html .= "<div class=\"announcementContainer\">";
    $html .= "<p class=\"title\">To Do</p>";
    foreach($todo as $item) {
      $html .= "" .
      "<div class=\"post\">" .
      "<div id=\"post-title\">".$item["title"]."</div>" .
      "<div id=\"post-created\">Created: ".$item["created"]."</div>" .
      "<div id=\"post-message\">".$item["message"]."</div>" .
      "</div>";
    }
    $html .= "";
    echo $html;
  }
  ?>
  </div>
  <div class="card-container">
    <div class="card-holder">
      <p class="card-title">Student Management</p>
      <p class="card-subtitle">
        Note: To remove, all data will  come from access
      </p>
      <a class="card-item" href="/student/show/">List All Students</a>
      <a class="card-item" href="/student/create/">Add Student</a>
      <a class="card-item" href="/student/modify/">Edit Student Info</a>
    </div>
    <div class="card-holder">
      <p class="card-title">Attendance</p>
      <a class="card-item" href="/attendance/search/">View by Student</a>
      <a class="card-item" href="/attendance/grade/">View by Grade</a>
      <a class="card-item" href="/attendance/search/">View by Section</a>
    </div>
  </div>

</div>
