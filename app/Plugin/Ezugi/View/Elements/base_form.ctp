<div class="row-fluid">
    <div class="span12">
        <?= $this->Form->create($model); ?>
            <label class="control-label"><?=__('From');?></label>
            <div class="form-group input-group col-lg-12 input-append date">
                <input name="data[<?=$model;?>][from]" id="from" type="text" class="form-control datetimepicker" value="<?= (!isset($from)? '': date('d M, Y',strtotime($from))); ?>">
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
            
            <label class="control-label"><?=__('To');?></label>
            <div class="form-group input-group col-lg-12 input-append date">
                <input name="data[<?=$model;?>][to]" id="to" type="text" class="form-control datetimepicker" value="<?= (!isset($to)? '': date('d M, Y',strtotime($to))); ?>">
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
            
            <?php if (!$only_dates): ?>
                <label class="control-label"><?=__('User ID');?></label>
                <div class="controls">
                    <div class="input-append">
                        <input name="data[Ezugi][user]" class="m-ctrl-medium" size="16" type="text" />
                        <span class="add-on"><i class="icon-user"></i></span>
                    </div>
                </div>

                <label class="control-label"><?=__('Game');?></label>
                <div class="controls">
                    <div class="input-append">
                        <select name="data[Ezugi][game]" class="m-ctrl-medium">
                            <option value="" selected><?=__('All');?></option>
                            <?php foreach ($games as $key => $g) { ?>
                                <option value="<?=$key;?>" <?=($game == $key)?"selected":"";?>><?=__($g);?></option>
                            <?php } ?>
                        </select>
                        <span class="add-on"><i class="icon-user"></i></span>
                    </div>
                </div>
            <?php endif; ?>
            
            
            <?php if ($show_tt): ?>
                <label class="control-label"><?=__('Transaction Type');?></label>
                <div class="controls">
                    <div class="input-append">
                        <select name="data[Ezugi][type]" class="m-ctrl-medium">
                            <option value="" selected><?=__('All');?></option>
                            <?php foreach ($types as $key => $t) { ?>
                                <option value="<?=$key;?>" <?=($type == $key)?"selected":"";?>><?=__($t);?></option>
                            <?php } ?>
                        </select>
                        <span class="add-on"><i class="icon-user"></i></span>
                    </div>
                </div>
            <?php endif; ?>
                
            <?php if ($show_bt): ?>
                <label class="control-label"><?=__('Bet Type');?></label>
                <div class="controls">
                    <div class="input-append">
                        <select name="data[Ezugi][type]" class="m-ctrl-medium">
                            <option value="-1" selected><?=__('All');?></option>
                            <?php foreach ($casinotypes as $key => $t) { ?>
                                <option value="<?=$key;?>" <?=($type == $key) ? "selected" : "";?>><?=__($t);?></option>
                            <?php } ?>
                        </select>
                        <span class="add-on"><i class="icon-user"></i></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <?= $this->Form->submit(__('Show', true), array('class' => 'btn')); ?>
            <?= $this->Form->end(); ?>
    </div>
</div>