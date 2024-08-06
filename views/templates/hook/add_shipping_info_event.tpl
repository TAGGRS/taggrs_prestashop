<script t-id="{$event_name}">
    $(function() {
        window.dataLayer = window.dataLayer || [];

        var rawEvent = {$encode_event nofilter};
        var carrierDetails = {$carrier_details nofilter};

        $('body').on('click', 'button[name="confirmDeliveryOption"]', function(){

            let currentCarrierId = $('input[name*="delivery_option"]:checked').val();
            if( carrierDetails[currentCarrierId] === undefined ){
                return false;
            } 

            let cartTotalPrice = carrierDetails[currentCarrierId]['optionExtraValue'] + prestashop.cart.subtotals.products.amount;

            rawEvent.ecommerce.value = cartTotalPrice;
            rawEvent.ecommerce.shipping_tier = carrierDetails[currentCarrierId]['optionEventName'];

            dataLayer.push(
                rawEvent
            );
        });
    });
</script>