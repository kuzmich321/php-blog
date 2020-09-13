<?php
namespace Core;

class Router {
    public static function route ($url) {

        // get rid of /blah-blah-blah/
        array_shift($url);

        // controller
        $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]) : DEFAULT_CONTROLLER;
        $controller_name = $controller;
        array_shift($url);

        // action
        $action = (isset($url[0]) && $url[0] != '') ? $url[0] . 'Action' : 'indexAction';
        $action_name = $controller;
        array_shift($url);

        // params
        $queryParams = $url;
        $controller = 'App\Controllers\\';

        $dispatch = new $controller($controller_name, $action);

        if(method_exists($controller, $action)) {
            call_user_func_array([$dispatch, $action], $queryParams);
        } else {
            die('Method does not exist in the controller "'.$controller_name.'"');
        }
    }

    public static function redirect($location) {
        if(!headers_sent()) {
            header('Location: '.GROOT.$location);
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.GROOT.$location.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.$location.'" />';
            echo '</noscript>';exit;
        }
    }
}
