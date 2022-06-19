<button class="btn edit btn-link mr-3" onclick="window.history.back();"><i class="fas fa-angle-double-left"></i> <?= __('Back'); ?></button>


<iframe style="width:100%;max-width:100%;height:1500px;" src="<?= $url; ?>?
        cashierKey=<?= $cashier_key; ?>&
        cashierToken=<?= $cashier_token; ?>&  
        singlePaymentMethod=<?= $single_payment_method; ?>&
        singlePaymentProvider=<?= $single_payment_provider; ?>&
        language=<?= $language; ?>&
        theme=transparent" frameborder="0" id="bridgerpay-iframe"></iframe>
<!--alwaysVisibleInputsForProviders=%7B%22[field_name]%22%3A%5B%22[psp_name,psp_name2]%22%5D%7D-->
<!--singlePaymentMethod=credit_card&-->
