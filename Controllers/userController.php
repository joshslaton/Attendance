<?php

class userController extends Controller
{
  public function login()
  {
    session_start();
    // if(isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == 1) {
    if($this->is_logged_in()) {
      header("Location: /");
    }else {
      $this->render("login");
    }
  }

  public function logout()
  {
    session_start();
    // if(isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == 1) {
    if($this->is_logged_in()) {
      session_destroy();
      header("Location: /user/login/");
    }else {
      header("Location: /user/login/");
    }
  }

  public function verify()
  {
    session_start();
    include_once("../Models/User.php");
    $u = new User();
    $email = strip_tags($_POST["user"]);
    $password = strip_tags($_POST["pass"]);
    $user = $u->get_user($email);
    if (password_verify($_POST["pass"], $user["password"])) {
      $_SESSION["login"] = $email;
      $_SESSION["isLoggedIn"] = 1;
      header("Location: /");
      exit();
    } else {
      $_SESSION["isLoggedIn"] = 0;
      header("Location: /user/login/");
      exit();
    }
  }

  public function not_found()
  {
    $this->render("404");
  }
}
