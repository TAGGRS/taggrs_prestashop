$(() => {
	if (typeof prestashop !== 'undefined') {
		const baseCartProducts = prestashop.cart.products;
		const baseCartInfo = {};

		// Initialize base cart info
		baseCartProducts.forEach(product => {
			const tempId = `${product.id_product}-${product.id_product_attribute}`;
			baseCartInfo[tempId] = product.cart_quantity;
		});

		const updateDataLayer = (eventName, currency, value, items) => {
			window.dataLayer = window.dataLayer || [];
			dataLayer.push({ ecommerce: null });
			dataLayer.push({
				'event': eventName,
				'ecommerce': {
					'currency': currency,
					'value': value,
					'items': items
				}
			});
		};

		const prepareCartItem = (product, quantity) => ({
			'item_id': product.id,
			'item_name': product.name,
			'price': product.price_with_reduction.toFixed(2),
			'item_category': product.category,
			'quantity': quantity
		});

		prestashop.on('updateCart', event => {
			const { linkAction, cart, idProduct, idProductAttribute } = event.reason;
			const loopTempId = `${idProduct}-${idProductAttribute}`;
			let cartItems = [];
			let itemPrice;
			let eventName;

			const product = baseCartProducts.find(
				p => parseInt(p.id) === parseInt(idProduct) && parseInt(p.id_product_attribute) === parseInt(idProductAttribute)
			);

			switch (linkAction) {
				case "add-to-cart": {
					const product = cart.products.find(
						p => parseInt(p.id) === parseInt(idProduct) && parseInt(p.id_product_attribute) === parseInt(idProductAttribute)
					);

					if (product) {
						const productQty = (baseCartInfo[loopTempId] !== undefined)
							? product.quantity - baseCartInfo[loopTempId]
							: product.quantity;

						cartItems.push(prepareCartItem(product, productQty));
						itemPrice = product.price_with_reduction.toFixed(2);
						eventName = 'add_to_cart';
						baseCartInfo[loopTempId] = product.quantity;
					}
					break;
				}

				case "delete-from-cart": {
					const product = baseCartProducts.find(
						p => parseInt(p.id) === parseInt(idProduct) && parseInt(p.id_product_attribute) === parseInt(idProductAttribute)
					);

					if (product) {
						cartItems.push(prepareCartItem(product, product.quantity));
						itemPrice = product.price_with_reduction.toFixed(2);
						eventName = 'remove_from_cart';
						product.quantity = 0;
					}
					break;
				}

				default: {
					const product = baseCartProducts.find(
						p => parseInt(p.id) === parseInt(event.resp.id_product) && parseInt(p.id_product_attribute) === parseInt(event.resp.id_product_attribute)
					);

					const cartproduct = prestashop.cart.products.find(
						p => parseInt(p.id) === parseInt(event.resp.id_product) && parseInt(p.id_product_attribute) === parseInt(event.resp.id_product_attribute)
					);

					if( product.quantity < cartproduct.quantity ){
    					eventName = 'add_to_cart';
						actionQty = cartproduct.quantity - product.quantity;
    				} else{
    					eventName = 'remove_from_cart';
						actionQty = product.quantity - cartproduct.quantity;
    				}

    				cartItems.push(prepareCartItem(product, actionQty));
    				product.quantity = event.resp.quantity;

					break;
				}
			}

			if (cartItems.length) {
				updateDataLayer(eventName, currCode, itemPrice, cartItems);
			}
		});
	}
});