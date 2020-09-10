<?php
use Core\Router;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

require_once (ROOT.DS.'config'.DS.'config.php');
require_once (ROOT.DS.'vendor'.DS.'autoload.php');
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

function autoload($className) {
    $classArr = explode('\\', $className);
    $class = array_pop($classArr);
    $subPath = strtolower(implode(DS, $classArr));
    $path = ROOT.DS.$subPath.DS.$class.'php';
    if (file_exists($path)) {
        require_once($path);
    }
}
