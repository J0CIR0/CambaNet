<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'CambaNet');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', 'http://172.20.10.3/CambaNet/public');
define('SITE_NAME', 'CambaNet');
define('EMAIL_HOST', 'smtp.gmail.com');
define('EMAIL_PORT', 587);
define('EMAIL_USERNAME', 'clarosrocajosue@gmail.com');
define('EMAIL_PASSWORD', 'yljowzoaufayfjjw');
define('EMAIL_FROM', 'clarosrocajosue@gmail.com');
define('EMAIL_FROM_NAME', 'CambaNet');
function url($action = '') {
    return BASE_URL . '/?action=' . $action;
}
function redirect($action) {
    header("Location: " . url($action));
    exit();
}
function asset($path) {
    return BASE_URL . '/' . ltrim($path, '/');
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('DEBUG_MODE', true);
?>