<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../autoload.php';

use App\Http\Request;

$http = new Request();
$http->addControl();
$response = $http->run();
$response();
