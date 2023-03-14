define([
    "jquery",
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/model/quote',
    'data_utils',
    'z1_connector_util'
], function ($, customerData, quote, dataUtils, z1Util) {
    "use strict";

    z1Util.setCheckout(true);

    var eventInitializaed = false;

    var cart = customerData.get('cart');
    cart.subscribe(function () {

        if (eventInitializaed == false) {

            eventInitializaed = true;
            // z1Util.pushEvent("begin_checkout", {});
            // initCheckoutPageEvents();
        }

    });

    z1Util.pushEvent("begin_checkout", {});
    initCheckoutPageEvents();



    function initCheckoutPageEvents() {
        var lastBillingAddress = null;
        var isFirstBillingLoaded = false;

        quote.billingAddress.subscribe(function (address) {
            if (address == null || lastBillingAddress == JSON.stringify(address)) {
                return;
            }
            if (isFirstBillingLoaded == false) {
                isFirstBillingLoaded = true;
                lastBillingAddress = JSON.stringify(address);
                return;
            }
            lastBillingAddress = JSON.stringify(address);
            z1Util.pushEvent("add_shipping_info", {});
        }, this);


        var lastShippingAddress = null;
        var isFirstShippingLoaded = false;

        quote.shippingAddress.subscribe(function (address) {
            if (address == null || lastShippingAddress == JSON.stringify(address)) {
                return;
            }
            if (isFirstShippingLoaded == false) {
                isFirstShippingLoaded = true;
                lastShippingAddress = JSON.stringify(address);
                return;
            }

            lastShippingAddress = JSON.stringify(address);
            z1Util.pushEvent("add_shipping_info", {});
        }, this);

        quote.paymentMethod.subscribe(function (data) {

            z1Util.pushEvent("add_payment_info", {});

        });

        $(document).on('change', 'input[name="payment[method]"]', function () {

            //z1Util.pushEvent("add_payment_info", {});

        });
    }

    return function (config) {


    }
});