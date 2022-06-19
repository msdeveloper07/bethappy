<?php
if (!$model)
    $model = 'Report';
?>
<?= $this->Form->create('Report'); ?>
<div class="form-row align-items-center justify-content-flex-start flex-wrap">

    <div class="form-group mr-1">
        <label for="From"><?= __('Date From'); ?></label>
        <div class="input-group">
            <input type="text" class="form-control datetimepicker-filter" aria-label="From" name="data[<?= $model; ?>][from]" autocomplete="off">
            <div class="input-group-append">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
            </div>
        </div>
    </div>


    <div class="form-group mr-1">
        <label for="From"><?= __('Date To'); ?></label>
        <div class="input-group">
            <input type="text" class="form-control datetimepicker-filter" aria-label="From" name="data[<?= $model; ?>][to]" autocomplete="off">
            <div class="input-group-append">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
            </div>
        </div>
    </div>

    <?php if ($payment_providers): ?>
        <div class="">
            <div class="form-group mr-1">
                <label for="Providers"><?= __('Providers'); ?></label>
                <select type="select" name="data[<?= $model; ?>][payment_provider]" class="form-control">
                    <option selected disabled><?php echo __('Select provider'); ?></option>
                    <?php foreach ($payment_providers as $key => $provider) { ?>
                        <option value="<?php echo $provider['payment_methods']['name']; ?>"><?php echo $provider['payment_methods']['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    <?php endif;
    ?>

    <?php if (!empty($game_providers)): ?>
        <div class="">
            <div class="form-group mr-1">
                <label><?= __('Providers'); ?></label>
                <select type="select" name="data[<?= $model; ?>][game_provider]" class="form-control">
                    <option selected disabled><?php echo __('Select provider'); ?></option>
                    <?php foreach ($game_providers as $key => $provider) { ?>
                        <option value="<?php echo $provider; ?>"><?php echo $provider; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    <?php endif;
    ?>
    <?php if ($payment_type): ?>
        <div class="">
            <div class="form-group mr-1">
                <label><?= __('Payment Type'); ?></label>
                <select type="select" name="data[<?= $model; ?>][payment_type]" class="form-control">
                    <option selected disabled><?php echo __('Select payment type'); ?></option>
                    <option value="Deposit"><?php echo __('Deposits'); ?></option>
                    <option value="Withdraw"><?php echo __('Withdraws'); ?></option>
                    <option value="Refund"><?php echo __('Refunds'); ?></option>
                </select>
            </div>
        </div>
    <?php endif;
    ?>
    <?php if ($status): ?>
        <div class="">
            <div class="form-group mr-1">
                <label><?= __('Status'); ?></label>
                <select type="select" name="data[<?= $model; ?>][status]" class="form-control">
                    <option selected disabled><?php echo __('Select status'); ?></option>
                    <option value="Pending"><?php echo __('Pending'); ?></option>
                    <option value="Completed"><?php echo __('Completed'); ?></option>
                    <option value="Failed"><?php echo __('Failed'); ?></option>
                    <option value="Cancelled"><?php echo __('Cancelled'); ?></option>
                    <option value="Declined"><?php echo __('Declined'); ?></option>
                </select>
            </div>
        </div>
    <?php endif;
    ?>

    <?php if ($report_type): ?>
        <div class="">
            <div class="form-group mr-1">
                <label><?= __('Report Type'); ?></label>

                <select type="select" name="data[<?= $model; ?>][report_type]" class="form-control">
                    <option selected disabled><?php echo __('Select report type'); ?></option>
                    <option value="summary"><?php echo __('Summary'); ?></option>
                    <option value="complete"><?php echo __('Complete'); ?></option>
                </select>
            </div>
        </div>

    <?php endif;
    ?>
    <?php if (!empty($currencies)): ?>
        <div class="">
            <div class="form-group mr-1">
                <label><?php echo __('Currencies'); ?></label>
                <select type="select" name="data[<?= $model; ?>][currency_id]" class="form-control">
                    <option selected disabled><?php echo __('Select currency'); ?></option>
                    <?php foreach ($currencies as $key => $currency) { ?>
                        <option value="<?php echo $currency['Currency']['id']; ?>"><?php echo $currency['Currency']['name']; ?></option>
                    <?php } ?>
                </select>

            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->Form->submit(__('Show', true), array('class' => 'btn btn-info')); ?>
<!--<?//= $this->Form->button('Clear', array('type'=>'reset', 'class' => 'btn btn-danger'));?>-->
<?= $this->Form->end(); ?>


