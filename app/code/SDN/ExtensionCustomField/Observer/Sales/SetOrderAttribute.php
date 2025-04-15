<?php

namespace SDN\ExtensionCustomField\Observer\Sales;

class SetOrderAttribute implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $order= $observer->getData('order');
        $order->setCustomOrderAttribute("ABC"); 
        $order->save();
    }
}
