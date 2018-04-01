<?php

use Slothsoft\Farah\Kernel;
use Slothsoft\Farah\RequestProcessor\PageRequestProcessor;

require_once __DIR__ . '/../vendor/autoload.php';

$path = isset($_SERVER['PATH_INFO'])
	? $_SERVER['PATH_INFO']
	: '';

Kernel::processPathRequest($path, new PageRequestProcessor());