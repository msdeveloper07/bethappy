<div class="ctrl">
    <div class="pull-left dctrl">
        <label class="control-label"><?php echo __('From');?></label>
        <div class="controls">
            <div class="input-append">
                <input name="data[<?php echo $model;?>][from]" class=" m-ctrl-medium datetimepicker" size="16" type="text" data-date-format="yyyy-mm-dd" utocomplete="off" />
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
        </div>
    </div>
    <div class="pull-left ctrl">
        <label class="control-label"><?php echo __('To');?></label>
        <div class="controls">
            <div class="input-append">
                <input name="data[<?php echo $model;?>][to]" class=" m-ctrl-medium datetimepicker" size="16" type="text" data-date-format="yyyy-mm-dd" utocomplete="off" />
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
        </div>
    </div>
</div>    
<div class="clearfix"></div>