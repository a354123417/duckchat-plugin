<?php
//$_SERVER['REQUEST_URI'] = "/User/100369-api.html";

$_ENV['WPF_URL_PATH_SUFFIX'] = '/wpf';


if (!empty($_GET['action'])) {
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    $action =  ucwords($action, '.');
    
    $controllerName  = str_replace(".", "_", $action);

    $_ENV['WPF_URL_CONTROLLER_NAME'] = $controllerName;
    $_ENV['WPF_URL_CONTROLLER_METHOD_PARAM_NAME'] = "doIndex";
}

if(!isset($_ENV['WPF_URL_CONTROLLER_NAME'])) {
    header("HTTP/1.1 401 Unauthorized");
    return ;
}
require_once(__DIR__ . "/lib/wpf/init.php");