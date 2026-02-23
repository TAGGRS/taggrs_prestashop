<script t-id="{$event_name|escape:'htmlall':'UTF-8'}">
    $(function() {
        window.dataLayer = window.dataLayer || [];

        const eventString = {$encode_event|escape:'javascript':'UTF-8'};
        const cleanEventString = rawEventString.replace(/&quot;/g, '"');
        const parseableCleanEvent = JSON.parse(cleanEventString);

        const carrierDetailsString = {$carrier_details|escape:'javascript':'UTF-8'};
        const cleanCarrierDetailsString = carrierDetailsString.replace(/&quot;/g, '"');
        const parseableCarrierDetails = JSON.parse(cleanCarrierDetailsString);


        $('body').on('click', 'button[name="confirmDeliveryOption"]', function(){

            let currentCarrierId = $('input[name*="delivery_option"]:checked').val();
            if( parseableCarrierDetails[currentCarrierId] === undefined ){
                return false;
            } 

            let cartTotalPrice = parseableCarrierDetails[currentCarrierId]['optionExtraValue'] + prestashop.cart.subtotals.products.amount;

            parseableCleanEvent.ecommerce.value = cartTotalPrice;
            parseableCleanEvent.ecommerce.shipping_tier = parseableCarrierDetails[currentCarrierId]['optionEventName'];

            dataLayer.push(
                parseableCleanEvent
            );
        });
    });
</script>