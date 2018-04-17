<?php

use Slothsoft\Farah\Kernel;
use Slothsoft\Farah\Http\MessageFactory;
use Slothsoft\Farah\RequestStrategy\LookupPageStrategy;
use Slothsoft\Farah\ResponseStrategy\SendHeaderAndBodyStrategy;

require_once __DIR__ . '/../vendor/autoload.php';

$requestStrategy = new LookupPageStrategy();
$responseStrategy = new SendHeaderAndBodyStrategy();

$request = MessageFactory::createServerRequest($_SERVER, $_REQUEST, $_FILES);

$kernel = new Kernel($requestStrategy, $responseStrategy);
$kernel->handle($request);