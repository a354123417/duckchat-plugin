<?php
//$_SERVER['REQUEST_URI'] = "/User/100369-api.html";

include_once './lib/sdk/class.dc_open_api.php';

$_ENV['WPF_URL_PATH_SUFFIX'] = '/wpf';

require_once (__DIR__ . "/lib/mock.php");

ini_set("display_errors", "Off");
ini_set("log_errors", "On");

if (!ini_get("error_log")) {
    ini_set("error_log", "php_error.log");
}

if (!empty($_GET['action'])) {
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    $action = ucwords($action, '.');

    $controllerName = str_replace(".", "_", $action);

    $_ENV['WPF_URL_CONTROLLER_NAME'] = $controllerName;
    $_ENV['WPF_URL_CONTROLLER_METHOD_PARAM_NAME'] = "doIndex";
}

if (!isset($_ENV['WPF_URL_CONTROLLER_NAME'])) {
    header("HTTP/1.1 401 Unauthorized");
    return;
}
require_once(__DIR__ . "/lib/wpf/init.php");