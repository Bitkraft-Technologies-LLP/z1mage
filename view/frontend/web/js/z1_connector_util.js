define([
    "jquery",
    'Magento_Customer/js/customer-data'
], function ($, customerData) {
    "use strict";

    var customerId = null;
    var isCheckoutPage = false;
    var isAddToCart = false;
    var isLoggingEnabled = false;

    var zineoneSendData = true;

    function generateRandomId() {

        const uint32 = window.crypto.getRandomValues(new Uint32Array(1))[0];
        return (uint32.toString(16) + ":" + Date.now());
    }

    function getLastKnowUserId() {
        return localStorage.getItem("z1_lastKnownUserId");
    }

    function setKnownUserId(userId) {
        localStorage.setItem("z1_lastKnownUserId", userId);
    }


    function getAnonymousUserId() {
        return localStorage.getItem("z1_anonymousId");
    }

    function setAnonymousUserId(anonymousId) {
        localStorage.setItem("z1_anonymousId", anonymousId);
    }

    function setLoggedInUserId(userId) {
        localStorage.setItem("z1_loggedInUserId", userId);
    }

    function populateCommonAttributes(currentPayloadObj) {

        var customer = customerData.get('customer');

        var loginStatus = (customer().firstname ? "Y" : "N");
        var anonymousId = getAnonymousUserId();

        //If no random id is generated then generate
        if (anonymousId == null) {
            anonymousId = generateRandomId();
            setAnonymousUserId(anonymousId);

        }

        var cartProducts = [];

        var cart = customerData.get('cart')();

        // if (customerId == "" || customerId == null || customerId == undefined) {

        //     currentPayloadObj["loginStatus"] = "N";
        //     currentPayloadObj["anonymousId"] = "";

        //     //Logout Detected
        //     if (anonymousId != getLastKnowUserId()) {
        //         setKnownUserId(anonymousId);
        //         //ZineOne.setCustomerId(anonymousId);
        //     }
        // }
        // else {
        //     currentPayloadObj["loginStatus"] = "Y";
        //     currentPayloadObj["customerId"] = customerId;
        //     cart = updatedCartObj;

        //     //Login Detected
        //     if (customerId != getLastKnowUserId()) {
        //         setLoggedInUserId(customerId);
        //         setKnownUserId(customerId);
        //         ZineOne.setCustomerId(customerId);

        //     }

        // }

        currentPayloadObj["loginStatus"] = loginStatus;
        if (loginStatus == "Y") {

            currentPayloadObj["customerId"] = customerId;
            cart = updatedCartObj;

            //Login Detected
            if (customerId != getLastKnowUserId()) {
                setLoggedInUserId(customerId);
                setKnownUserId(customerId);
                ZineOne.setCustomerId(customerId);

            }

        }
        else {
            currentPayloadObj["anonymousId"] = "";

            //Logout Detected
            if (anonymousId != getLastKnowUserId()) {
                setKnownUserId(anonymousId);
                //ZineOne.setCustomerId(anonymousId);
            }
        }

        for (var i = 0; i < cart.summary_count; i++) {
            var cartObj = cart.items[i];
            var tempObj = {};
            if (cartObj) {
                tempObj["quantity"] = cartObj.qty;
                tempObj["productId"] = cartObj["product_id"];
                tempObj["priceSale"] = cartObj["product_price_value"];
                tempObj["priceOrig"] = cartObj["product_price_value"];
                tempObj["sku"] = cartObj["product_sku"];
                cartProducts[cartProducts.length] = tempObj;
            }

        }

        currentPayloadObj["cartProducts"] = JSON.stringify(cartProducts);




        return currentPayloadObj;

    }

    var logFunction = function (...logString) {

        if (isLoggingEnabled) {
            console.log(...logString);
        }


    }


    function okToPush(eventName, payload) {
        let finalPayload = populateCommonAttributes(payload);
        finalPayload["eventName"] = eventName;

        logFunction("finalPayload: ", finalPayload);
        if (eventName == "purchase") {
            if (finalPayload["products"]) {
                finalPayload["products"] = JSON.stringify(finalPayload["products"])
            }
        }

        logFunction("zineoneSendData: ", zineoneSendData);
        if (zineoneSendData) {
            logFunction("Pushing Data to Z1: ", zineoneSendData);
            ZineOne.pushEvent(eventName, finalPayload);
        }
    }

    var cartUpdatedFlag = false;
    var cartIsSubscribed = false;
    var updatedCartObj = null;

    var returnObj = {

        log: logFunction,

        pushEvent: function (eventName, payload) {

            this.log("into pushEvent");

            if (customerId != "" && customerId != null && customerId != undefined) {

                var cart = customerData.get('cart');
                updatedCartObj = cart();
                // console.log("Current Cart", cart());

                // cartUpdatedFlag = false;

                // cart.subscribe(function (updatedCustomer) {
                //     console.log("cart Updated now", updatedCustomer);

                //     updatedCartObj = updatedCustomer;

                //     if (cartUpdatedFlag == false) {
                //         cartUpdatedFlag = true;
                //         okToPush(eventName, payload);
                //     }

                // });

                // return;

            }
            else {
                cartUpdatedFlag = false;
                cartIsSubscribed = false;
                updatedCartObj = null;
            }


            okToPush(eventName, payload);






        },

        setCustomerID: function (customerIdRef) {
            this.log("setCustomerID: ", customerIdRef);
            customerId = customerIdRef;
        },

        setZineoneApiKey: function (zineoneApiKey) {
            if (zineoneSendData) {
                ZineOne.initialize(zineoneApiKey);
            }

        },

        setZineoneSendData: function (sendData) {

            zineoneSendData = sendData == 0 ? false : true;
        },

        setLogging(logging) {

            isLoggingEnabled = logging == 0 ? false : true;;

        },


        setCheckout: function () {
            isCheckoutPage = true;
        },

        isCheckout: function () {
            return isCheckoutPage;
        },

        setAddToCart(bool) {
            isAddToCart = bool;
        },

        isAddToCart() {
            return isAddToCart;
        }

    };

    return returnObj;
});