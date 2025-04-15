// define(['jquery'], function ($) {
//     'use strict';
//
//     return function (target) {
//         return target.extend({
//             placeOrder: function (data, event) {
//                 // Check if the street[0] input is empty
//                 var streetInput = $('.admin__control-fields .input-text[name="street[0]"]');
//                 if (streetInput.length > 0 && !streetInput.val()) {
//                     streetInput.val('No address');  // Set "No address" if street[0] is empty
//                 }
//
//                 // Call the original placeOrder function
//                 return this._super(data, event);
//             }
//         });
//     };
// });

// define(['jquery'], function($) {
//     'use strict';
//
//     return function (defaultRenderer) {
//         return defaultRenderer.extend({
//             /**
//              * Update street address field with default value if empty
//              */
//             updateStreetAddress: function () {
//                 // Check if the street address is empty
//                 if (!this.street()) {
//                     // Set default value for street address
//                     this.street('Default Street Address');
//                 }
//             }
//         });
//     };
// });

define(['jquery', 'mage/utils/wrapper'], function ($, wrapper) {
    'use strict';

    var mixin = {
        placeOrder: function (originalAction, messageContainer, data) {
            // Check if street address is empty
            if (!data['shippingAddress']['street'] || data['shippingAddress']['street'].length === 0) {
                // Set default value for street address
                data['shippingAddress']['street'] = ['Default Street Address'];
            }

            // Call the original placeOrder function
            return originalAction(messageContainer, data);
        }
    };

    return function (target) {
        return wrapper.wrap(target, function (originalFunction, messageContainer, data) {
            return mixin.placeOrder(originalFunction, messageContainer, data);
        });
    };
});




