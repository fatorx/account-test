<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../autoload.php';

use App\App;

$response = (new App())->addControl()->run();
$response();
