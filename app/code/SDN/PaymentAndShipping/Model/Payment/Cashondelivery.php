<?php
declare(strict_types=1);

namespace SDN\PaymentAndShipping\Model\Payment;

class Cashondelivery extends \Magento\Payment\Model\Method\AbstractMethod
{

    protected $_code = "cashondelivery";
    protected $_isOffline = true;

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
}

