define(['jquery', 'Magento_Customer/js/customer-data', 'data_utils', 'z1_connector_util'],

    function ($, customerData, dataUtils, z1Util) {

        var cart = customerData.get('cart');
        var count = cart().summary_count;
        cart.subscribe(function () {
            if (cart().summary_count !== count) {
                count = cart().summary_count;

                lastCartStr = localStorage.getItem("z1_lastcart");
                if (lastCartStr == null) {
                    lastCartStr = "[]";
                }

                newCart = cart()["items"];

                newCartStr = JSON.stringify(newCart);

                //Check to See cart updation
                switch (checkCart(lastCartStr, newCartStr)) {
                    case 1:
                        //z1Util.pushEvent("webAddToCart", {});
                        z1Util.setAddToCart(false);
                        break;

                    case 2:

                        if (z1Util.isAddToCart() == true) {
                            z1Util.setAddToCart(false);
                            return;
                        }
                        if (z1Util.isCheckout() == false) {
                            z1Util.pushEvent("webUpdateCart", {});
                        }

                        break;
                }


                //Update LS if cart change
                if (lastCartStr != newCartStr) {
                    //console.log("Updating Cart LS",JSON.stringify(cart()["items"]));
                    localStorage.setItem("z1_lastcart", JSON.stringify(cart()["items"]));
                }

            }
        });





        function checkCart(oldCartStr, newCartStr) {

            let oldCartIteams = JSON.parse(oldCartStr);
            let newCartItems = JSON.parse(newCartStr);

            //old cart Items selected Properties.
            let oldCartItemsProperty = JSON.stringify(oldCartIteams
                .map(item => {
                    return {
                        qty: item.qty,
                        product_id: item.product_id,
                        product_sku: item.product_sku
                    }
                })
            );

            //new cart Items selected Properties
            let newCartItemsProperty = JSON.stringify(newCartItems
                .map(item => {
                    return {
                        qty: item.qty,
                        product_id: item.product_id,
                        product_sku: item.product_sku
                    }
                })
            );

            // add to cart
            if (oldCartIteams.length < newCartItems.length) {
                return 1;
            }

            //update to cart
            if (oldCartItemsProperty != newCartItemsProperty) {
                return 2;
            }

            //0: No Update
            return 0;

        }

    }



);