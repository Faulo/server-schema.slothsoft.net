<?php
declare(strict_types = 1);
namespace Slothsoft\Server\Schema\Tests;

use Slothsoft\FarahTesting\Module\AbstractTestCase;
use Slothsoft\Farah\Http\MessageFactory;
use Slothsoft\Farah\RequestStrategy\LookupPageStrategy;
use DOMDocument;

final class IndexTest extends AbstractTestCase {
    
    public function testIndex() {
        $_SERVER['REQUEST_URI'] = '/';
        
        $requestStrategy = new LookupPageStrategy();
        
        $request = MessageFactory::createServerRequest($_SERVER, $_REQUEST, $_FILES);
        
        $response = $requestStrategy->process($request);
        
        $data = (string) $response->getBody();
        
        $doc = new DOMDocument();
        $this->assertTrue($doc->loadXML($data));
    }
}
