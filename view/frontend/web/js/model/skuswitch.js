define([
    'jquery',
    'mage/utils/wrapper',
    'z1_connector_util'
], function ($, wrapper, z1Util) {
    'use strict';

    return function (targetModule) {

        var reloadPrice = targetModule.prototype._reloadPrice;
        var reloadPriceWrapper = wrapper.wrap(reloadPrice, function (original) {
            var result = original();
            var simpleSkuObj = this.options.spConfig.skus[this.simpleProduct];

            if (simpleSkuObj && simpleSkuObj["sku"] != '') {

                var simpleSku = simpleSkuObj["sku"];

                $('div.product-info-main .sku .value').html(simpleSku);

                z1Util.pushEvent("webviewedsku", simpleSkuObj);
            }
            return result;
        });
        targetModule.prototype._reloadPrice = reloadPriceWrapper;
        return targetModule;
    };
});