<style type="text/css">
    .ctrl .dctrl { padding-right: 10px; }
</style>

<?php 
    if (empty($model) || $model == null) $model = 'Transaction';
    echo $this->Form->create($model);
    echo $this->element('dates_form', array('model' => $model));
?>
<div class="clearfix"></div>
<?php if ($slotId == 1): ?>
    <label class="control-label"><?php echo __('Transaction ID');?></label>
    <div class="controls">
        <div class="input-append">
            <input name="data[<?=$model;?>][id]" class="m-ctrl-medium" size="16" type="text" />
            <span class="add-on"><i class="icon-user"></i></span>
        </div>
    </div>
<?php endif; ?>

<?php if ($slotRemoteId == 1): ?>
    <label class="control-label"><?php echo __('Remote ID');?></label>
    <div class="controls">
        <div class="input-append">
            <input name="data[<?=$model;?>][remote_id]" class="m-ctrl-medium" size="16" type="text" />
            <span class="add-on"><i class="icon-user"></i></span>
        </div>
    </div>
<?php endif; ?>

<?php if ($slotAmountFrom == 1): ?>
    <label class="control-label"><?php echo __('Amount From');?></label>
    <div class="controls">
        <div class="input-append">
            <input name="data[<?=$model;?>][amount_from]" class="m-ctrl-medium" size="16" type="text" />
            <span class="add-on"><i class="icon-user"></i></span>
        </div>
    </div>
<?php endif; ?>

<?php if ($slotAmountTo == 1): ?>
    <label class="control-label"><?php echo __('Amount To');?></label>
    <div class="controls">
        <div class="input-append">
            <input name="data[<?=$model;?>][amount_to]" class="m-ctrl-medium" size="16" type="text" />
            <span class="add-on"><i class="icon-user"></i></span>
        </div>
    </div>
<?php endif; ?>
    
<div class="ctrl">
    <?php if ($users): ?>
        <div class="pull-left dctrl">
            <label class="control-label"><?php echo __('User');?></label>
            <div class="controls">
                <div class="input-append">
                    <input id="writeusername" name="data[<?=$model;?>][username]" class="m-ctrl-medium" size="16" type="text" onkeyup="getUserVal(this)" value="<?=($selected_username ? $selected_username : '');?>"/>
                    <span class="add-on"><i class="icon-user"></i></span>
                </div>
                <?php echo $this->Form->input('user_id', array('label' => false, 'id' => 'rep_users_list', 'div' => 'input select', 'onChange' => 'selectUser(this.value)', 'type' => 'select', 'options' => array())); ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($types)): ?>
        <div class="pull-left dctrl">
            <label class="control-label"><?php echo __('Transaction Type');?></label>
            <div class="controls">
                <div class="input-append">
                    <select type="select" name="data[<?=$model;?>][type]">
                        <option selected disabled><?php echo __('Select Type');?></option>
                        <?php foreach ($types as $key => $type) { ?>
                            <option value="<?php echo $key;?>" <?= (($selected_type && $selected_type == $key)?'selected':'');?>><?php echo __($type);?></option>
                        <?php } ?>
                    </select>
                    <span class="add-on"><i class="icon-asterisk"></i></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($games)): ?>
        <div class="pull-left dctrl">
            <label class="control-label"><?php echo __('Game');?></label>
            <div class="controls">
                <div class="input-append">
                    <select type="select" name="data[<?=$model;?>][game]">
                        <option selected disabled><?php echo __('Select Game');?></option>
                        <?php foreach($games as $key => $game){ ?>
                            <option value="<?php echo $key;?>"><?php echo $game;?></option>
                        <?php } ?>
                    </select>
                    <span class="add-on"><i class="icon-play"></i></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="clearfix"></div>

<?php if ($amounts): ?>
    <div class="ctrl">
        <div class="pull-left dctrl">
            <label class="control-label"><?php echo __('Amount');?></label>
            <div class="controls">
                <div class="input-append">
                    <input name="data[<?=$model;?>][amount]" class="m-ctrl-medium" size="16" type="text" />
                    <span class="add-on"><i class="icon-barcode"></i></span>
                </div>
            </div>
        </div>

        <div class="pull-left dctrl">
            <label class="control-label"><?php echo __('Amount From');?></label>
            <div class="controls">
                <div class="input-append">
                    <input name="data[<?=$model;?>][amount_from]" class="m-ctrl-medium" size="16" type="text" />
                    <span class="add-on"><i class="icon-barcode"></i></span>
                </div>
            </div>
        </div>

        <div class="pull-left dctrl">
            <label class="control-label"><?php echo __('Amount To');?></label>
            <div class="controls">
                <div class="input-append">
                    <input name="data[<?=$model;?>][amount_to]" class="m-ctrl-medium" size="16" type="text" />
                    <span class="add-on"><i class="icon-barcode"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
<?php endif; ?>
        
<?php echo  $this->Form->submit(__('Show', true), array('class' => 'btn')); ?>
<?php echo  $this->Form->end(); ?>
        
<script type="text/javascript">
    $('#rep_users_list').hide();
    
    function getUserVal(e) { getUsers(e.value); };
    
    function getUsers(username) {
        $.ajax({
            dataType: "json",
            url: "/admin/users/getUsers/"+username,
            method: "GET", 
            success: function(data) {
                if (data.length > 0){
                    $('#rep_users_list').html("");
                    $("#rep_users_list").append($("<option>--</option>"));
                    $.each(data, function(i){
                        $("#rep_users_list").append($("<option>",{ value: data[i].u.id, text: "(" + data[i].u.id + ") " + data[i].u.username, selected: (username == data[i].u.username) ? true : false }));
                    });
                    $('#rep_users_list').show();
                }
            }
        });
    };
    
    function selectUser(val){
        $('#user_id').val(val);
    };
    
    (function() {
        var usernameval = $("input#writeusername").val();
        if (usernameval && usernameval != "" && usernameval != null) {
            getUsers(usernameval);
            $('#rep_users_list').show();
        }
    })();

</script>