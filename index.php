<?php
use Core\Router;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

require_once (ROOT . DS . 'config' . DS . 'config.php');
require_once (ROOT . DS . 'app' . DS . 'lib' . DS . 'helpers.php');
require_once (ROOT . DS . 'vendor' . DS . 'autoload.php');
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

function autoload($className){
    $classAry = explode('\\', $className);
    $class = array_pop($classAry);
    $subPath = strtolower(implode(DS, $classAry));
    $path = ROOT . DS . $subPath . DS . $class . '.php';
    if(file_exists($path)){
        require_once($path);
    }
}

spl_autoload_register('autoload');
session_start();

$url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];

// Route the request
Router::route($url);

