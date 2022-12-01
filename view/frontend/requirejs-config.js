var config = {
    deps: [
        'Zineone_Z1Connector/js/default'

    ],
    map: {
        '*': {
            z1_connector_util: 'Zineone_Z1Connector/js/z1_connector_util',
            data_utils: 'Zineone_Z1Connector/js/data_utils',
            product_page: 'Zineone_Z1Connector/js/product_page',
            cart_page: 'Zineone_Z1Connector/js/cart_page',
            home_page: 'Zineone_Z1Connector/js/home_page',
            search_page: 'Zineone_Z1Connector/js/search_page',
            checkout_page: 'Zineone_Z1Connector/js/checkout_page',
            category_page: 'Zineone_Z1Connector/js/category_page',
            checkout_onepage_success: 'Zineone_Z1Connector/js/checkout_onepage_success',
            commondata: 'Zineone_Z1Connector/js/commondata'
        }
    },
    config: {
        mixins: {
            'Magento_ConfigurableProduct/js/configurable': {
                'Zineone_Z1Connector/js/model/skuswitch': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'Zineone_Z1Connector/js/model/swatch-skuswitch': true
            },
            'Magento_Catalog/js/catalog-add-to-cart': {
                'Zineone_Z1Connector/js/model/catalog-add-to-cart-mixin': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'Zineone_Z1Connector/js/model/set-shipping-information-mixin': true
            }
        }
    }
}
