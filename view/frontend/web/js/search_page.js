define([
    "jquery",
    'Magento_Customer/js/customer-data',
    'data_utils',
    'z1_connector_util',
], function($, customerData, dataUtils, z1Util) {
    "use strict";
    return function(config) {

        //z1Util.log("Search Data",config);
        var payload = {};
        payload["searchTerm"] = config["searchterms"];
        z1Util.pushEvent("webSearchResults", payload);
    }
});