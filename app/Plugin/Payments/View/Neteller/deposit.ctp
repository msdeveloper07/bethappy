<div class="neteller">
    <div class="container">
        <div class="row row-heading">
            <div class="btn-icon">
                <?= $this->Html->link('<i class="fas fa-angle-double-left"></i>', $this->request->referer(), array('escape' => false), array('class' => 'btn-icon')) ?>
            </div>
            <div class="">
                <img src="/plugins/payments/neteller.png" class=""/>
            </div>
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
        <!--        <div class="row">
                    <div class="col">
                        <div class="message-success text-center d-none">
                            <div class="form-group">
                                <p style="color: green"><i class="fa fa-check-circle fa-5x"></i></p>
                                <p><?= __('Form is valid.'); ?></p>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>-->
        <?= $this->MyForm->create('Neteller', array('novalidate' => true)); ?>

        <div class="form-group col-8 offset-2">
            <label for="data[netellerID]"><?= __('Neteller ID or Email address') ?></label>
            <input id="depositform-netellerID" class="form-control netellerID-control" name="data[netellerID]" type="text" required data-parsley-group="group-3"/>
        </div>

        <div class="form-group col-8 offset-2">
            <label for="data[secureCode]"><?= __('Security Code') ?></label>
            <input id="depositform-secureCode" class="form-control netellerCode-control" name="data[secureCode]"  type="password" required data-parsley-group="group-3"/>
        </div>
        <div class="form-group submit-group">
            <!--<input type="submit" class="btn btn-default" value="validate">-->
            <?= $this->MyForm->button(__('Deposit Now', true), array('type' => 'submit', 'class' => 'btn-deposit', 'name' => 'payment', 'value' => $method['pay_methods']['name'], 'div' => false)); ?> 
        </div> 
        <div class="loading-wrapper text-center"></div>

        <?= $this->MyForm->end(); ?>
    </div>
</div>


<script>
    $(function () {
        $('#NetellerDepositForm').parsley()
                .on('field:validated', function () {
                    var valid = $('.parsley-error').length === 0;
                    $('.message-success').toggleClass('d-none', !valid);
                    $('.message-danger').toggleClass('d-none', valid);
                })
                .on('form:submit', function () {
                    $('.btn-deposit').hide();
                    $('.loading-wrapper').append('<div id="loading"></div>');
                });
    });
</script>