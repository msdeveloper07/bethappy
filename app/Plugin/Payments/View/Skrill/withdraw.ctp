<div class="neteller">
    <div class="container">
         <div class="row row-heading">
            <div class="btn-icon">
                <?= $this->Html->link('<i class="fas fa-angle-double-left"></i>', $this->request->referer(), array('escape' => false), array('class' => 'btn-icon')) ?>
            </div>
            <div class="">
                <img src="/plugins/payments/skrill.png" class=""/>
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

        <?= $this->MyForm->create('Skrill', array('novalidate' => true)); ?>

        <div class="form-group col-8 offset-2">
            <label for="data[netellerID]"><?= __('Skrill ID or Email address') ?></label>
            <input id="depositform-netellerID" class="form-control netellerID-control" name="data[netellerID]" type="text" required data-parsley-group="group-3"/>
        </div>

     
        <div class="form-group submit-group">
            <?= $this->MyForm->button(__('Request Withdraw', true), array('type' => 'submit', 'class' => 'btn-deposit', 'name' => 'payment', 'value' => $method['pay_methods']['name'], 'div' => false)); ?> 
        </div>                           
    </div>
</div>


<script>
    $(function () {
        $('#SkrillWithdrawForm').parsley()
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