$(function() {

	if (typeof prestashop !== 'undefined') {

		var baseCartProducts = prestashop.cart.products;
		var baseCartInfo = [];
		for (let i = 0; i < prestashop.cart.products.length; i++) {
			let tempId = prestashop.cart.products[i].id_product + '-' + prestashop.cart.products[i].id_product_attribute;
			baseCartInfo[tempId] = prestashop.cart.products[i].cart_quantity;
		}

		prestashop.on(
			'updateCart',
			function (event) {

		    	if( event.reason.linkAction == "add-to-cart"){

		    		var cartItems = [];
		    		for (let i = 0; i < event.reason.cart.products.length; i++) {

		    			if( parseInt(event.reason.cart.products[i].id) == parseInt(event.reason.idProduct)
		    				&& parseInt(event.reason.cart.products[i].id_product_attribute) == parseInt(event.reason.idProductAttribute) ){

		    				let loopTempId = event.reason.idProduct + '-' + event.reason.idProductAttribute;

		    				if( baseCartInfo[loopTempId] !== undefined ){
		    					var productQty = event.reason.cart.products[i].quantity - baseCartInfo[loopTempId];
		    				} else{
		    					var productQty = event.reason.cart.products[i].quantity;
		    				}

						    var item = {
						    	'item_id': event.reason.cart.products[i].id,
						    	'item_name': event.reason.cart.products[i].name,
						    	'price': event.reason.cart.products[i].price_with_reduction.toFixed(2),
						    	'item_category': event.reason.cart.products[i].category,
						    	'quantity': productQty,
						    };

						    baseCartInfo[loopTempId] = event.reason.cart.products[i].quantity;

						    var itemPrice = event.reason.cart.products[i].price_with_reduction.toFixed(2);
						    cartItems.push(item);
					    }
					}

		    		window.dataLayer = window.dataLayer || [];
					dataLayer.push({ ecommerce: null });
					dataLayer.push({
						'event': 'add_to_cart',
						'ecommerce': {
							'currency': currCode,
							'value': itemPrice,
							'items': cartItems
						}
					});
		    	} else if( event.reason.linkAction == "delete-from-cart" ){

		    		var cartItems = [];
		    		for (let i = 0; i < baseCartProducts.length; i++) {

		    			if( parseInt(baseCartProducts[i].id) == parseInt(event.reason.idProduct)
		    				&& parseInt(baseCartProducts[i].id_product_attribute) == parseInt(event.reason.idProductAttribute) ){

		    				let loopTempId = event.reason.idProduct + '-' + event.reason.idProductAttribute;
						    var item = {
						    	'item_id': event.reason.idProduct,
						    	'item_name': baseCartProducts[i].name,
						    	'price': baseCartProducts[i].price_with_reduction.toFixed(2),
						    	'item_category': baseCartProducts[i].category,
						    	'quantity': baseCartProducts[i].quantity,
						    };

						    var itemPrice = baseCartProducts[i].price_with_reduction.toFixed(2);

						    baseCartProducts[i].quantity = 0;
						    cartItems.push(item);
					    }
					}

				    window.dataLayer = window.dataLayer || [];
					dataLayer.push({ ecommerce: null });
					dataLayer.push({
						'event': 'remove_from_cart',
						'ecommerce': {
							'currency': currCode,
							'value': itemPrice,
							'items': cartItems
						}
					});
		    	} else{

		    		var cartItems = [];
					let eventName = '';
					let actionQty = 0;

		    		for (let i = 0; i < baseCartProducts.length; i++) {

		    			if( parseInt(baseCartProducts[i].id) == parseInt(event.reason.id_product)
		    				&& parseInt(baseCartProducts[i].id_product_attribute) == parseInt(event.reason.id_product_attribute) ){

		    				let loopTempId = event.reason.id_product + '-' + event.reason.id_product_attribute;

		    				if( event.reason.quantity < baseCartProducts[i].quantity){
		    					eventName = 'remove_from_cart';
		    					actionQty = baseCartProducts[i].quantity - event.reason.quantity;
		    				} else{
		    					eventName = 'add_to_cart';
		    					actionQty = event.reason.quantity - baseCartProducts[i].quantity;
		    				}

						    var item = {
						    	'item_id': event.reason.id_product,
						    	'item_name': baseCartProducts[i].name,
						    	'price': baseCartProducts[i].price_with_reduction.toFixed(2),
						    	'item_category': baseCartProducts[i].category,
						    	'quantity': actionQty,
						    };

						    var itemPrice = event.reason.cart.products[i].price_with_reduction.toFixed(2);

						    baseCartProducts[i].quantity = event.reason.quantity;
						    cartItems.push(item);
					    }
					}

				    window.dataLayer = window.dataLayer || [];
					dataLayer.push({ ecommerce: null });
					dataLayer.push({
						'event': eventName,
						'ecommerce': {
							'currency': currCode,
							'value': itemPrice,
							'items': cartItems
						}
					});
		    	}
	    	}
	  	);
	}
});