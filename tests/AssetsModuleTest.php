<?php
declare(strict_types = 1);

use Slothsoft\Farah\FarahUrl\FarahUrlAuthority;
use Slothsoft\Farah\ModuleTests\AbstractModuleTest;

class AssetsModuleTest extends AbstractModuleTest
{
    protected static function getManifestAuthority() : FarahUrlAuthority {
        return FarahUrlAuthority::createFromVendorAndModule('slothsoft', 'schema.slothsoft.net');
    }
}