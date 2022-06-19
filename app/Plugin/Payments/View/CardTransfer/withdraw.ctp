<!-- Bank Transfer form -->
<div class="card-transfer">
    <div class="container">
        <div class="row">  
            <div class="col-md-12">

                <div class="card default-payment-card">
                    <div class="card-header">
                        <button class="btn edit btn-link mr-3" onclick="window.history.back();"><i class="fas fa-angle-double-left"></i></button>  
                        <h3>Card transfer</h3>
                    </div>
                    <div class="card-body">

                        <?= $this->MyForm->create('CardTransfer', array('novalidate' => true)); ?>

                        <div class="form-group col-8 offset-2">
                            <label for="data[ctFirstName]"><?= __('First Name') ?></label>
                            <input id="depositform-ctFirstName" class="form-control" name="data[ctFirstName]" type="text" required/>

                        </div>

                        <div class="form-group col-8 offset-2">
                            <label for="data[ctLastName]"><?= __('Last Name') ?></label>
                            <input id="depositform-ctLastName" class="form-control" name="data[ctLastName]"  type="text" required/>
                        </div>

                        <div class="form-group col-8 offset-2">
                            <label for="data[ctCardNumber]"><?= __('Card Number') ?></label>
                            <input id="depositform-ctCardNumber" class="form-control" name="data[ctCardNumber]" type="text" required/>
                        </div>
                     

                        <div class="form-group submit-group text-center mx-auto">
                            <?= $this->MyForm->button(__('Request withdraw', true), array('type' => 'submit', 'class' => 'btn btn-default rounded-pill px-4 btn-deposit', 'name' => 'payment', 'value' => 'CT', 'div' => false)); ?> 
                        </div>  


                        <?= $this->MyForm->end(); ?>   

                    </div>
                </div>

                <!--<div class="row">
                            <div class="col">
                                <div class="message-danger text-center d-none">
                                    <div class="form-group">
                                        <p style="color: red"><i class="fa fa-exclamation-triangle fa-5x"></i></p>
                                        <p><?= __('Form invalid. All fileds are required.'); ?></p>
                                        <hr>
                
                                    </div>
                                </div>
                            </div>
                        </div>-->
            </div>
        </div>
    </div>
</div>


<script>
    $(function () {
        $('#CardTransferWithdrawForm').parsley()
                .on('field:validated', function () {
                    var valid = $('.parsley-error').length === 0;
                    $('.message-success').toggleClass('d-none', !valid);
                    $('.message-danger').toggleClass('d-none', valid);
                })
                .on('form:submit', function () {
                    $('.btn-deposit').hide();
                    $('.submit-group').append('<div id="loading"></div>');
                });
    });
</script>
