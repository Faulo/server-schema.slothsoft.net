<?php
declare(strict_types = 1);
namespace Slothsoft\Server\Schema\Tests;

use Slothsoft\Farah\Http\MessageFactory;
use Slothsoft\FarahTesting\Module\AbstractTestCase;
use Slothsoft\Farah\RequestStrategy\LookupPageStrategy;
use DOMDocument;

class IndexTest extends AbstractTestCase {

    public function testIndex() {
        $_SERVER['REQUEST_URI'] = '/';

        $requestStrategy = new LookupPageStrategy();

        $request = MessageFactory::createServerRequest($_SERVER, $_REQUEST, $_FILES);

        $response = $requestStrategy->process($request);

        $data = stream_get_contents($response->getBody()->detach());

        $doc = new DOMDocument();
        $this->assertTrue($doc->loadXML($data));
    }
}

