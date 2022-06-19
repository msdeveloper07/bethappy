<style>
    .card-wrapper{
        background-color: #001E39;
        background-image: linear-gradient(30deg, rgba(255,255,255,0) 70%, rgba(255,255,255,0.2) 70%),linear-gradient(45deg, rgba(255,255,255,0) 75%, rgba(255,255,255,0.2) 75%),linear-gradient(60deg, rgba(255,255,255,0) 80%, rgba(255,255,255,0.2) 80%);
        max-width: 40em;
        border-radius: 5px;
        padding: 12px;
        margin: 0 auto;
    }
    input, select{
        font-size: 12px!important;
    }
    .d-flex .form-group{
        width: 49%;
        margin:0 auto;
    }
</style>

<div class="icanpay">
    <div class="container">
        <div class="row row-heading">
            <a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER']); ?>" class="btn-icon"><i class="fas fa-angle-double-left"></i></a>
            <img src="/plugins/payments/icanpay.png" class=""/>
        </div>

        <br>

        <div class="row">
            <div class="col">
                <div class="message-danger text-center d-none">
                    <div class="form-group">
                        <p style="color: red"><i class="fa fa-exclamation-triangle fa-5x"></i></p>
                        <p><?= __('Form invalid. All fileds are required.'); ?></p>
                        <hr>

                    </div>
                </div>
            </div>
        </div>

        <?= $this->MyForm->create('Icanpay', array('novalidate' => true)); ?>
        <div class="card-wrapper">
            <div class="form-group col-12 text-right p-0">
                <!--<img src="../img/payment-methods/visa_sm.png" alt=""/>-->
            </div>
            <div class="form-group col-12 p-0">
                <label for="data[card-number]"><?= __('Card Number') ?></label>
                <input id="depositform-card-number" class="form-control card-number-control" name="data[card-number]" type="text" placeholder="<?= __('Card Number') ?>" required data-parsley-group="group-3"/>
            </div>
            <div class="form-group d-flex p-0">
                <div class="form-group p-0">
                    <label for="data[card-holder-first-name]"><?= __('Cardholder Name') ?></label>
                    <input id="depositform-card-holder" class="form-control card-holder-control" name="data[card-holder-first-name]" type="text" placeholder="<?= __('Cardholder Name') ?>" required data-parsley-group="group-3"/>
                </div>
                <div class="form-group p-0">
                    <label for="data[card-holder-last-name]"><?= __('Cardholder Surname') ?></label>
                    <input id="depositform-card-holder" class="form-control card-holder-control" name="data[card-holder-last-name]" type="text" placeholder="<?= __('Cardholder Surname') ?>" required data-parsley-group="group-3"/>
                </div>
            </div>

            <div class="form-group d-flex p-0">
                <div class="form-group p-0">
                    <label for="data[card-expiry-date]"><?= __('Expiry Date') ?></label>
                    <select id="depositform-card-expiry-date" class="form-control card-expiry-date-control" name="data[card-expiry-date]"  required data-parsley-group="group-3">
                        <?php foreach ($expiration_date as $date): ?>
                            <option value="<?= $date; ?>"><?= $date; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group p-0">
                    <label for="data[card-cvv]"><?= __('CVV') ?></label>
                    <input id="depositform-card-CVV" class="form-control card-CVV-control" name="data[card-CVV]" type="text" placeholder="<?= __('CVV') ?>" required data-parsley-group="group-3"/>
                </div>

            </div>


            <div class="form-group col-12 text-right p-0 submit-group">
                          <!--<input type="submit" class="btn btn-default" value="validate">-->
                <?= $this->MyForm->button(__('Deposit Now', true), array('type' => 'submit', 'class' => 'btn-deposit', 'name' => 'payment', 'value' => $method['pay_methods']['name'], 'div' => false)); ?> 
            </div>
            <div class="loading-wrapper text-center"></div>
        </div>



        <?= $this->MyForm->end(); ?>
        <script type="text/javascript" src="https://pay.icanpay.cn.com/pay/js?key=<?= $public_key; ?>&form=IcanpayDepositForm"></script>
    </div>
</div>


<script>

    $('#IcanpayDepositForm').parsley()
            .on('field:validated', function () {
                var valid = $('.parsley-error').length === 0;
                $('.message-success').toggleClass('d-none', !valid);
                $('.message-danger').toggleClass('d-none', valid);
            })
            .on('form:submit', function () {
                $('.btn-deposit').hide();
                $('.loading-wrapper').append('<div id="loading"></div>');
            });

</script>

