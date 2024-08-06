<script t-id="{$event_name}">
    $(function() {
        window.dataLayer = window.dataLayer || [];

        var rawEvent = {$encode_event nofilter};
        $('body').on('click', 'div#payment-confirmation button[type="submit"]', function(){

            let currentPaymentName = $('.payment-option input:checked').closest('.payment-option').find('label').text();
            if( currentPaymentName.length <= 0 ){
                return false;
            }

            rawEvent.ecommerce.payment_type = currentPaymentName.trim();
            dataLayer.push(
                rawEvent
            );
        });
    });
</script>