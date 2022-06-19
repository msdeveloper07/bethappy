<!-- Bank Transfer form -->
<div class="bank-transfer">
    <div class="container">
        <div class="row">  
            <div class="col-md-12">

                <div class="card default-payment-card">
                    <div class="card-header">
                        <button class="btn edit btn-link mr-3" onclick="window.history.back();"><i class="fas fa-angle-double-left"></i></button>  
                        <h3>Bank transfer</h3>
                    </div>
                    <div class="card-body">

                        <?= $this->MyForm->create('BankTransfer', array('novalidate' => true)); ?>

                        <div class="form-group col-8 offset-2">
                            <label for="data[btFirstName]"><?= __('First Name') ?></label>
                            <input id="depositform-btFirstName" class="form-control" name="data[btFirstName]" type="text" required/>

                        </div>

                        <div class="form-group col-8 offset-2">
                            <label for="data[btLastName]"><?= __('Last Name') ?></label>
                            <input id="depositform-btLastName" class="form-control" name="data[btLastName]"  type="text" required/>
                        </div>

                        <div class="form-group col-8 offset-2">
                            <label for="data[btBankName]"><?= __('Bank Name') ?></label>
                            <input id="depositform-btBankName" class="form-control" name="data[btBankName]" type="text" required/>
                        </div>
                        <div class="form-group col-8 offset-2">
                            <label for="data[btBICSWIFT]"><?= __('BIC/SWIFT Code') ?></label>
                            <input id="depositform-btBICSWIFT" class="form-control" name="data[btBICSWIFT]" type="text" required/>
                        </div>
                        <div class="form-group col-8 offset-2">
                            <label for="data[btIBAN]"><?= __('IBAN') ?></label>
                            <input id="depositform-btIBAN" class="form-control" name="data[btIBAN]" placeholder="" type="text" required/>
                        </div>

                        <div class="form-group submit-group text-center mx-auto">
                            <?= $this->MyForm->button(__('Request withdraw', true), array('type' => 'submit', 'class' => 'btn btn-default rounded-pill px-4 btn-deposit', 'name' => 'payment', 'value' => 'BT', 'div' => false)); ?> 
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
        $('#BankTransferWithdrawForm').parsley()
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
