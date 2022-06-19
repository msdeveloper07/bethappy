<div id="withdraws" class="settings">
    <h3><?= __('Bank info'); ?></h3>
    <h4><?= __('Please provide your bank info'); ?></h4>
    <?= $this->element('flash_message'); ?>
    <?php
    echo $this->Form->create('User', array(
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class' => 'regi',
            # define error defaults for the form    
            'error' => array(
                'wrap' => 'span',
                'class' => 'my-error-class'
            )
        )
    ));
    ?>
    <table class="default-table">
        <tr>
            <td><label><?= __('Bank name'); ?></label></td>
            <td><?= $this->Form->input('bank_name', array('value' => $user['bank_name'])); ?></td>
        </tr>
        <tr>
            <td><label><?= __('Account number'); ?></label></td>
            <td><?= $this->Form->input('account_number', array('value' => $user['account_number'])); ?></td>
        </tr>
    </table>
    <div class="centered">
        <?= $this->Form->submit(__('Confirm changes'), array('class' => 'button')); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>