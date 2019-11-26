<?php

class homeController extends Controller
{
  public function index()
  {
    include_once("../Models/DB.php");
    $todo = DB::query(array("SELECT * FROM proj_todo ORDER BY id DESC"));
    $d["todo"] = $todo;
    session_start();
    if(isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == 1) {
      $this->set($d);
      $this->render("index");
    }else {
      header("Location: /user/login/");
      exit();
    }
  }
}
