<?php
declare(strict_types = 1);
use Slothsoft\Farah\Kernel;
use Slothsoft\Farah\ModuleTests\AbstractTestCase;
use Slothsoft\Farah\RequestProcessor\PageRequestProcessor;

class IndexTest extends AbstractTestCase
{
    public function testIndex()
    {
        ob_start();
        Kernel::processPathRequest('/', new PageRequestProcessor());
        $data = ob_get_contents();
        ob_end_clean();
        $doc = new DOMDocument();
        $this->assertTrue($doc->loadXML($data));
    }
}

