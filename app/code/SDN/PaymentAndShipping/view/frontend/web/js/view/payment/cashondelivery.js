define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'cashondelivery',
                component: 'SDN_PaymentAndShipping/js/view/payment/method-renderer/cashondelivery-method'
            }
        );
        return Component.extend({});
    }
);