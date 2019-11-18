<?php

class Router {
    public static function parse($url, $request) {
        $explode_url = explode('/', trim($url, "/"));
        $request->controller = $explode_url[0] == null ? "home" : $explode_url[0];
        // $request->controller = $explode_url[0] == null ? "home" : $explode_url[0];
        // if($explode_url[0] == null) {
        //     $request->controller = "home";
        // }else {
        //     switch($explode_url[0]) {
        //         case "home": $request->controller = "home"; break;
        //         case "plugins": $request->controller = "plugins"; break;
        //         default: "home"; break;
        //     }
        // }
        $request->action = !isset($explode_url[1]) ? "" : $explode_url[1];

        $request->params = array_slice($explode_url, 2);
        // $request->params = str_replace($request->params, "?", "");
    }
}
