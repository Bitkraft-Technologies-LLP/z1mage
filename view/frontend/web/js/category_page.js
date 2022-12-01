define([
    "jquery",
    'data_utils',
    'z1_connector_util'
], function($, dataUtils, z1Util) {
    "use strict";
    return function(config) {

        var payload = {};
        payload["category"] = config["category"];
        z1Util.pushEvent("webCategoryPage",payload);
    }
});