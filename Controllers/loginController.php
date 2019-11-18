<?php

class loginController extends Controller{

    function index() {
        $this->render("index");
    }

    function not_found() {
        $this->render("404");
    }
}