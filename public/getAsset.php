<?php

use Slothsoft\Farah\Kernel;
use Slothsoft\Farah\RequestStrategy\AssetRequestStrategy;
use Slothsoft\Farah\ResponseStrategy\EchoResponseStrategy;

require_once __DIR__ . '/../vendor/autoload.php';

$path = $_SERVER['REQUEST_URI'];

if (strpos($path, $_SERVER['SCRIPT_NAME']) === 0) {
    $path = substr($path, strlen($_SERVER['SCRIPT_NAME']) + 1);
}

if (strpos($path, 'farah://') !== 0) {
    $path = "farah://$path";
}

$kernel = new Kernel(new AssetRequestStrategy(), new EchoResponseStrategy());
$kernel->processPath($path);