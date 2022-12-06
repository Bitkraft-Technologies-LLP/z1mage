define([
    "jquery",
    'data_utils',
    'Magento_Customer/js/customer-data',
    'z1_connector_util'
], function ($, dataUtils, customerData, z1Util) {
    "use strict";
    return function (config) {

        var eventSent = false;
        var cart = customerData.get('cart');

        z1Util.log("Into cart_page.js", config, cart);

        cart.subscribe(function () {

            if (eventSent == false) {
                eventSent = true;
                z1Util.pushEvent("webViewCart", {});
            }


        }, this);

    }
});