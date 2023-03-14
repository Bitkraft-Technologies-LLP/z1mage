define([
    "jquery",
    'data_utils',
    'z1_connector_util'
], function($, dataUtils, z1Util) {
    "use strict";
    return function(config) {
        z1Util.pushEvent("view_product",config);
    }
});