define([
    "jquery",
    'Magento_Customer/js/customer-data',
    'z1_connector_util',
], function ($, customerData, z1Util) {
    "use strict";
    return function (config) {

        z1Util.setCustomerID(config["customerID"]);
        z1Util.setZineoneSendData(config["zineone_send_data"]);
        z1Util.setZineoneApiKey(config["zineone_api_key"]);
        z1Util.setLogging(config["zineone_logging_enabled"]);

    }
});