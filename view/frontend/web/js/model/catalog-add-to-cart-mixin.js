define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'z1_connector_util'
], function ($, customerData, z1Util) {
    'use strict';

    return function (widget) {
        $.widget('mage.catalogAddToCart', widget, {
            /**
             * @param {String} form
             */
            ajaxSubmit: function (form) {
                var self = this;

                $(self.options.minicartSelector).trigger('contentLoading');
                self.disableAddToCartButton(form);

                $.ajax({
                    url: form.attr('action'),
                    data: form.serialize(),
                    type: 'post',
                    dataType: 'json',

                    /** @inheritdoc */
                    beforeSend: function () {
                        if (self.isLoaderEnabled()) {
                            $('body').trigger(self.options.processStart);
                        }
                    },

                    /** @inheritdoc */
                    success: function (res) {
                        //alert(JSON.stringify(res));
                        var eventData, parameters;

                        $(document).trigger('ajax:addToCart', form.data().productSku);

                        if (self.isLoaderEnabled()) {
                            $('body').trigger(self.options.processStop);
                        }

                        if (res.backUrl) {
                            eventData = {
                                'form': form,
                                'redirectParameters': []
                            };
                            // trigger global event, so other modules will be able add parameters to redirect url
                            $('body').trigger('catalogCategoryAddToCartRedirect', eventData);

                            if (eventData.redirectParameters.length > 0) {
                                parameters = res.backUrl.split('#');
                                parameters.push(eventData.redirectParameters.join('&'));
                                res.backUrl = parameters.join('#');
                            }
                            window.location = res.backUrl;

                            return;
                        }

                        if (res.messages) {
                            $(self.options.messagesSelector).html(res.messages);
                        }

                        if (res.minicart) {
                            $(self.options.minicartSelector).replaceWith(res.minicart);
                            $(self.options.minicartSelector).trigger('contentUpdated');
                        }

                        if (res.product && res.product.statusText) {
                            $(self.options.productStatusSelector)
                                .removeClass('available')
                                .addClass('unavailable')
                                .find('span')
                                .html(res.product.statusText);
                        }
                        self.enableAddToCartButton(form);


                        z1Util.setAddToCart(true);

                        var isSubscribedCalled = false;
                        var cart = customerData.get('cart');
                        cart.subscribe(function () {

                            
                            if(isSubscribedCalled == false)
                            {   
                                z1Util.setAddToCart(false);
                                z1Util.pushEvent("webAddToCart",res);
                            }
                           
                            isSubscribedCalled = true;

                        });
                        

                    }
                });
            }
        });

        return $.mage.catalogAddToCart;
    }
});