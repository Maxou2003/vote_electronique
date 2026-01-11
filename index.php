<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once('./Utils/functions.php');
require_once(__DIR__.'/Utils/env.php');
load_env(__DIR__.'/.env');

// Auto load pour les namespaces
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));

    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});


$url = isset($_GET['p']) ? rtrim($_GET['p'], '/') : 'vote/index';
$urlParts = explode('/', $url);

$controllerName = ucfirst($urlParts[0]) . 'Controller';
$actionName = isset($urlParts[1]) ? $urlParts[1] : 'index';


// Full path to the controller file
$controllerPath = './Controllers/' . $controllerName . '.php';
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controllerClass = '\\App\\Controllers\\' . $controllerName;

    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();

        if (method_exists($controller, $actionName)) {
            $controller->$actionName(); // Call the method
        } else {
            http_response_code(404);
            echo "Error 404: Method '$actionName' not found in $controllerClass.";
        }
    } else {
        http_response_code(404);
        echo "Error 404: Class '$controllerClass' not found.";
    }
} else {
    http_response_code(404);
    echo "Error 404: Controller '$controllerName' not found.";
}