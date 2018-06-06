<?php
declare(strict_types = 1);

use Slothsoft\Farah\ModuleTests\AbstractXmlManifestTest;

class AssetsManifestTest extends AbstractXmlManifestTest
{
    protected static function getManifestDirectory(): string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets';
    }
    
}