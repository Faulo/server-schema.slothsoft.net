<?php

use Slothsoft\Farah\Kernel;
use Slothsoft\Farah\RequestStrategy\PageRequestStrategy;
use Slothsoft\Farah\ResponseStrategy\EchoResponseStrategy;

require_once __DIR__ . '/../vendor/autoload.php';

$path = $_SERVER['PATH_INFO'] ?? '';

$kernel = new Kernel(new PageRequestStrategy(), new EchoResponseStrategy());
$kernel->processPath($path);