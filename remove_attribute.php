<?php
use Magento\Framework\App\Bootstrap;

require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$eavSetup = $objectManager->create('\Magento\Eav\Setup\EavSetup');
$eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'delivery_note');
