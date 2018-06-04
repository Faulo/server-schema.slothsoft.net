<?php
namespace Slothsoft\Farah;

include __DIR__ . '/../../global/slothsoft.core.php';
include __DIR__ . '/../../global/slothsoft.core.xslt.php';
//include __DIR__ . '/../../global/slothsoft.core.dbms.php';
include __DIR__ . '/../../global/slothsoft.farah.php';
//include __DIR__ . '/../../global/slothsoft.minecraft.php';
//include __DIR__ . '/../../global/slothsoft.mtg.php';

Kernel::setCurrentSitemap('farah://slothsoft@schema.slothsoft.net/sitemap');
Dictionary::setSupportedLanguages('en-us');