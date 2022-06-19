<!--$has_error == false &&-->
<div class="container-fluid">
    <?php if ($has_error == false && $methods): ?>

        <!--Deposit form--> 
        <?= $this->MyForm->create('Deposit', array('novalidate' => true, 'data-parsley-validate' => '', 'class' => 'needs-validation')); ?>
        <div class="row">
            <!-- <div class="col-md-12">
                <div class="form-group">
                    
                    <label for="amount"><?= __('Amount'); ?></label>
                    <input type="text" class="form-control" id="amount" name="data[amount]" type="text" value="" 
                           pattern="[0-9]+([\,|\.][0-9]+)?" data-parsley-pattern="[0-9]+([\,|\.][0-9]+)?" 
                           data-parsley-group="group-1"
                           data-parsley-min="<?= $minDeposit; ?>" data-parsley-max="<?= $maxDeposit; ?>" 
                           required autocomplete="off"/>
                    <small id="amountHelp" class="form-text font-italic text-muted"><?= __('Minimum amount is %s.', $minDeposit); ?></small>
                </div>
            </div> -->

            <!--GENERATE PAYMENT OPTIONS-->

            <div class="col-md-12">
                <p><?= __('Select your preferred deposit method: '); ?></p>

                <ul class="payment-list">
                    <?php foreach ($methods as $method): ?>
                        <li class="payment-element" id="<?= $method['payment_methods']['slug']; ?>">
                            <div class="payment-card">
                                <?php if ($method['payment_methods']['slug'] == 'bank-transfer' || $method['payment_methods']['slug'] == 'card-transfer'): ?>
                                    <span class="text-center"><?= $method['payment_methods']['name'] ?></h4></span>
                                <?php else: ?>
                                    <img src="https://bh.msztsol.com/<?= $method['payment_methods']['image']; ?>" width=auto alt="<?= $method['pay_methods']['name']; ?>">
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach;
                    ?>
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
                                    <img src="https://bh.msztsol.com/<?= $method['payment_methods']['image']; ?>" class="method-logo"/>
                                <?php endif; ?>
                            </div>
                        </div> 

                    <?php endforeach;
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-12 text-center mt-4" id="terms-of-use">
            <div class="form-group terms-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="terms" id="terms" value="1" class="terms" required checked/>
                    <label class="custom-control-label" for="terms"><?= __('I accept the'); ?> <a class="text-link-default" href="#!/terms-of-use" target="_blank"><?= __('Terms of use') ?></a></label>
                </div>
            </div>

            <div class="form-group submit-group">
                <?= $this->MyForm->button(__('Continue', true), array('type' => 'submit', 'class' => 'btn btn-default rounded-pill px-4 btn-deposit validate', 'name' => 'payment', 'div' => false)); ?>
            </div> 
            <div class="loading-wrapper text-center"></div>
        </div>

        <?= $this->MyForm->end(); ?>
    <?php endif; ?>


</div>
<style>
/*    #forum-pay, #bank-transfer{
        display: none;
    }*/
</style>
<script>

    // Change iframe height according content size
    function resizeIframe() {
        let iframe = parent.document.querySelector("#deposit-iframe");
        iframe.style.height = iframe.contentWindow.document.body.offsetHeight + 'px';
    }
    
    $(document).ready(function() {
        resizeIframe();
    });

    // $('.radiant-pay, .forum-pay, .bank-transfer').hide();
    $('.chosen-payment-option').hide();
    $('#terms-of-use').hide();

    $(function () {
        $('#DepositIndexForm').parsley().on('field:validated', function () {
        })
        .on('form:submit', function () {
            $('.btn-deposit').hide();
            $('.loading-wrapper').append('<div id="loading"></div>');
        });
    });


    $(document).on('click', '.payment-element', function () {
        $('.payment-element').removeClass("active");
        // $('.radiant-pay, .bank-transfer, .forum-pay').hide();
        $('.chosen-payment-option').hide();

        var id = $(this).attr('id');
        $('.' + id).show();
        $(this).addClass("active");
        $('.' + id).show();
        $('#terms-of-use').show();
        $('.btn-deposit').val(id);

        resizeIframe();
    });

</script>