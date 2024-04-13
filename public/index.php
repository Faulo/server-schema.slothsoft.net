<?php

use Slothsoft\Farah\Kernel;
use Slothsoft\Farah\Http\MessageFactory;
use Slothsoft\Farah\RequestStrategy\LookupAssetStrategy;
use Slothsoft\Farah\RequestStrategy\LookupPageStrategy;
use Slothsoft\Farah\ResponseStrategy\SendHeaderAndBodyStrategy;

require_once __DIR__ . '/../vendor/autoload.php';

$request = MessageFactory::createServerRequest($_SERVER, $_REQUEST, $_FILES);
if (preg_match('~^/[^/]+@[^/]+~', $request->getUri()->getPath())) {
    $requestStrategy = new LookupAssetStrategy();
} else {
    $requestStrategy = new LookupPageStrategy();
}
$responseStrategy = new SendHeaderAndBodyStrategy();

$kernel = Kernel::getInstance();
$kernel->handle($requestStrategy, $responseStrategy, $request);