<?php

use Slothsoft\Farah\Kernel;
use Slothsoft\Farah\RequestProcessor\PageRequestProcessor;

require_once __DIR__ . '/../vendor/autoload.php';

$path = $_SERVER['REQUEST_URI'];

$path = explode('?', $path, 2)[0];

Kernel::processPathRequest($path, new PageRequestProcessor());