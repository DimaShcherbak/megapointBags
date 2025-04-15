<?php

namespace SDN\SendCoupon\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddCustomVariable implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\App\RequestInterface $request */
        $request = $observer->getData('request');

        // Get the customer data from the request
        $customerData = $request->getPost('customer');

        // Add your custom variable to the email template variables
        $templateVars = $observer->getData('template_vars');
        $templateVars['custom_variable'] = 'Hello, World!';

        // Update the template variables in the observer
        $observer->setData('template_vars', $templateVars);
    }
}
