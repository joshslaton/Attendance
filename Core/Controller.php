<?php

class Controller {

    public $vars = [];
    public $layout = "default";

    function set($d) {
        $this->vars = array_merge($this->vars, $d);
    }

    function render($filename) {
        extract($this->vars);
        ob_start();
        require(ROOT . "Views/" . ucfirst(str_replace('Controller', '', get_class($this))) . '/' . $filename . '.php');
        $content_for_layout = ob_get_clean();
        if ($this->layout == false) { // What is this ?
            $content_for_layout;
        } else {
            require(ROOT . "Views/Layouts/" . $this->layout . '.php');
        }
    }

    function renderNoHeaders($filename) {
        extract($this->vars);
        ob_start();
        require(ROOT . "Views/" . ucfirst(str_replace('Controller', '', get_class($this))) . '/' . $filename . '.php');
        $content_for_layout = ob_get_clean();
        if ($this->layout == false) { // What is this ?
            $content_for_layout;
        } else {
            require(ROOT . "Views/Layouts/plain.php");
        }
    }

    function renderNoMenu($filename) {
        extract($this->vars);
        ob_start();
        require(ROOT . "Views/" . ucfirst(str_replace('Controller', '', get_class($this))) . '/' . $filename . '.php');
        $content_for_layout = ob_get_clean();
        if ($this->layout == false) { // What is this ?
            $content_for_layout;
        } else {
            require(ROOT . "Views/Layouts/nomenu.php");
        }
    }

    function ajax($filename) {
        extract($this->vars);
        ob_start();
        // require(ROOT . "Views/Modules/" . ucfirst(str_replace('Controller', '', get_class($this))) . '/' . $filename . '.php');
        require(ROOT . "Views/Modules/$filename.php");
        $content_for_layout = ob_get_clean();
        if ($this->layout == false) { // What is this ?
            $content_for_layout;
        } else {
            require(ROOT . "Views/Layouts/plain.php");
        }
    }

    // TODO: Report when encountered.
    function not_found() {
        ob_start();
        require(ROOT . "Views/404.php");
        $content_for_layout = ob_get_clean();
        if ($this->layout == false) { // What is this ?
            $content_for_layout;
        } else {
            require(ROOT . "Views/Layouts/" . $this->layout . '.php');
        }
    }

    function is_logged_in() {
      if(isset($_SESSION["isLoggedIn"])) {
        return $_SESSION["isLoggedIn"] == 1 ? true : false;
      }
    }
}
