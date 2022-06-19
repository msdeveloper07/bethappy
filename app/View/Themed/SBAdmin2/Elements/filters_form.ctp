<?= $this->Form->create('Report'); ?>
<div class="">
    <label class="control-label"><?= __('From'); ?></label>
    <div class="controls">
        <div class="input-append date">
            <input name="data[Report][from]" class="m-ctrl-medium datetimepicker" size="16" type="text" data-date-format="yyyy-mm-dd" autocomplete="off"/>
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
    </div>
</div>
<div class="">
    <label class="control-label"><?= __('To'); ?></label>
    <div class="controls">
        <div class="input-append date">
            <input name="data[Report][to]" class="m-ctrl-medium datetimepicker" size="16" type="text" data-date-format="yyyy-mm-dd" autocomplete="off"/>
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
    </div>
</div>


<?php if ($providers): ?>
    <div class="">
        <label class="control-label"><?= __('Providers'); ?></label>
        <div class="controls">
            <div class="input-append">
                <select type="select" name="data[<?= $model; ?>][provider]">
                    <option selected disabled><?php echo __('Select provider'); ?></option>
                    <?php foreach ($providers as $key => $provider) { ?>
                        <option value="<?php echo $provider; ?>"><?php echo $provider; ?></option>
                    <?php } ?>
                </select>
                <span class="add-on"><i class="fas fa-gamepad"></i></span>
            </div>
        </div>
    </div>

<?php endif;
?>

<?php if (!empty($currencies)): ?>

    <div class="span4">
        <label class="control-label"><?php echo __('Currencies'); ?></label>
        <div class="controls">
            <div class="input-append">
                <select type="select" name="data[<?= $model; ?>][currency_id]">
                    <option selected disabled><?php echo __('Select currency'); ?></option>
                    <?php foreach ($currencies as $currency) { ?>
                        <option value="<?php echo $key; ?>"><?php echo $currency; ?></option>
                    <?php } ?>
                </select>
                <span class="add-on"><i class="fas fa-dollar-sign"></i></span>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->Form->submit(__('Show', true), array('class' => 'btn')); ?>
<?= $this->Form->end(); ?>