<?php

class Dispatcher
{
    private $request;
    /*
    
    */
    public function dispatch()
    {
        $this->request = new Request();
        Router::parse($this->request->url, $this->request);
        $controller = $this->loadController();
        if($this->request->action == null){
            // No action = Index Page
            call_user_func_array([$controller, "index"], $this->request->params);
        }else {
            if(method_exists($controller, $this->request->action)) {
                call_user_func_array([$controller, $this->request->action], $this->request->params);
            }else{
                call_user_func_array([$controller, "not_found"], $this->request->params);
            }
        }
    }
    public function loadController()
    {
        $name = $this->request->controller . "Controller";
        $file = ROOT . 'Controllers/' . $name . '.php';
        if(is_file($file) && file_exists($file)) {
            require($file);
            $controller = new $name();
            return $controller;
        }else {
            header("Location: /home/");
        }
    }
}