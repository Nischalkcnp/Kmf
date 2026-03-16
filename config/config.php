<?php
/**
 * KMF Website - Main configuration
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('BASE_URL', '/'); // Change if in subfolder e.g. '/kmf/'

date_default_timezone_set('Asia/Kathmandu');

session_start();

require_once ROOT_PATH . 'config/database.php';
require_once ROOT_PATH . 'includes/functions.php';
