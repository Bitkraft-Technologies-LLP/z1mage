define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'z1_connector_util'
], function ($, wrapper, quote, z1Util) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            /**
             * Chance to modify shipping addres data
             */
            var shippingAddressData = quote.shippingAddress();

            /**
             * Log before the original function
             */

            var result = originalAction();

            /**
             * Log after the original function
             */

            z1Util.pushEvent("add_shipping_info", {});

            return result;
        });
    };
});