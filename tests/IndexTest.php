<?php
declare(strict_types = 1);
use Slothsoft\Farah\Kernel;
use Slothsoft\Farah\ModuleTests\AbstractTestCase;
use Slothsoft\Farah\RequestStrategy\PageRequestStrategy;
use Slothsoft\Farah\ResponseStrategy\EchoResponseStrategy;

class IndexTest extends AbstractTestCase
{
    public function testIndex()
    {
        ob_start();
        $kernel = new Kernel(new PageRequestStrategy(), new EchoResponseStrategy());
        $kernel->processPath('/');
        $data = ob_get_contents();
        ob_end_clean();
        $doc = new DOMDocument();
        $this->assertTrue($doc->loadXML($data));
    }
}

