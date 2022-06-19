<!--$has_error == false &&--> 
<div class="container-fluid">
    <?php if ($has_error == false && $methods): ?>

        <!--Withdraw form--> 
        <?= $this->MyForm->create('Withdraw', array('novalidate' => true, 'data-parsley-validate' => '')); ?>
        <div class="row">

            <!--GENERATE PAYMENT OPTIONS-->
            <div class="col-md-12">
                <p><?= __('Select your preffered withdraw method: '); ?></p>
                <ul class="payment-list">
                    <?php foreach ($methods as $method): ?>
                        <li class="payment-element" id="<?= $method['payment_methods']['slug']; ?>">
                            <div class="payment-card">
                                <?php if ($method['payment_methods']['slug'] == 'bank-transfer' || $method['payment_methods']['slug'] == 'card-transfer'): ?>
                                    <span class="text-center"><?= $method['payment_methods']['name'] ?></h4></span>
                                <?php else: ?>
                                    <img src="<?= $method['payment_methods']['image']; ?>" width=auto alt="<?= $method['pay_methods']['name']; ?>">
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <hr>

            <!--PAYMENTS-->
            <div class="col-md-12">
                <div class="payment-content">
                    <?php
                    foreach ($methods as $method):
                        ?>
                        <div class="<?= $method['payment_methods']['slug']; ?> text-center chosen-payment-option">
                            <p><?= __('By clicking the continue button you will be redirected to ' . $method['payment_methods']['name'] . ' payment page.') ?></p>
                            <div class="payment-card">
                                <?php if ($method['payment_methods']['slug'] == 'bank-transfer' || $method['payment_methods']['slug'] == 'card-transfer'): ?>
                                    <span class="text-center"><?= $method['payment_methods']['name'] ?></span>
                                <?php else: ?>
                                    <img src="<?= $method['payment_methods']['image']; ?>" class="method-logo"/>
                                <?php endif; ?>
                            </div>
                        </div> 

                    <?php endforeach;
                    ?>
                </div>
            </div>


            <div class="col-md-12 text-center mt-4"  id="terms-of-use">
                <div class="form-group terms-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="terms" id="terms" value="1" class="terms" required checked/>
                        <label class="custom-control-label" for="terms"><?= __('I accept the'); ?> <a href="/#!/terms-of-use" class="text-link-default" target="_blank"><?= __('Terms of use') ?></a></label>
                    </div>
                </div>

                <div class="form-group submit-group mb-0">
                    <?= $this->MyForm->button(__('Continue', true), array('type' => 'submit', 'class' => 'btn btn-default px-4 btn-withdraw validate', 'name' => 'payment', 'div' => false)); ?>
                </div> 
            </div>

            <?= $this->MyForm->end(); ?>

        </div>

    <?php endif; ?>


    <?php if (!$methods): ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <?= __('There are no withdrawal methods available for your country. Please contact support on <a class="" href="mailto:' . Configure::read('Settings.websiteEmail') . '">' . Configure::read('Settings.websiteEmail') . '</a>'); ?>.
            </div>
        </div>
    <?php endif; ?>

</div>

<script type="text/javascript">

    // Change iframe height according content size
    function resizeIframe() {
        let iframe = parent.document.querySelector("#withdraw-iframe");
        iframe.style.height = iframe.contentWindow.document.body.offsetHeight + 'px';
    }
    
    $(document).ready(function() {
        resizeIframe();
    });

    $('.chosen-payment-option').hide();
    $('#terms-of-use').hide();
    $('#amount-section').hide();

    $(document).on('click', '.payment-element', function () {
        $('.payment-element').removeClass("active");
        $('.cashlib, .aninda-havale, .aninda-papara, .aninda-ccw, .aninda-mefete, .aninda-btc').hide();

        var id = $(this).attr('id');


        if (id === 'aninda-havale' || id === 'aninda-papara' || id === 'aninda-ccw' || id === 'aninda-mefete' || id === 'aninda-qr' || id === 'aninda-btc') {
            $('#amount').removeAttr('required');
            $('#amount-section').hide();
        } else {
            $('#amount').attr("required", true);
            $('#amount-section').show();
        }

        $('.' + id).show();
        $(this).addClass("active");
        $('.' + id).show();
        $('#terms-of-use').show();
        $('.btn-withdraw').val(id);

        resizeIframe();
    });


    $(function () {
        $('#WithdrawIndexForm').Parsley.on('field:validated', function () {
            //            var valid = $('.parsley-error').length === 0;
            //            $('.message-success').toggleClass('d-none', !valid);
            //            $('.message-danger').toggleClass('d-none', valid);
        })
        .on('form:submit', function () {
            $('.btn-withdraw').hide();
            $('.submit-group').append('<div id="loading"></div>');
        });
    });

</script>

