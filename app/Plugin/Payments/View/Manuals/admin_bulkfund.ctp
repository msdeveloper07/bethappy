<link rel="stylesheet" href="/Libs/fastselect/fastselect.css">
<script src="/Libs/fastselect/fastselect.js"></script>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => _($singularName))))); ?></div>
    </div>
    <?= $this->element('flash_message'); ?>
    <div id="page" class="dashboard">
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?php if($currencies): ?>
                                    <?=$this->Form->create();?>
                                        <?php echo __('Select Currency:');?>
                                        <select type="select" name="data[Paymentmanual][currency_id][]" class="multipleSelect" multiple name="currencies">
                                            <?php foreach($currencies as $currency){ ?>
                                                <option value="<?=$currency['Currency']['id'];?>"><?=$currency['Currency']['name'];?></option>
                                            <?php } ?>
                                        </select>
                                        <br> 
                                        <?php echo __('Select Country:');?>
                                        <select type="select" name="data[Paymentmanual][country][]" class="multipleSelect" multiple name="countries">
                                            <?php foreach($countries as $country){ ?>
                                                <option value="<?=$country['users']['country'];?>"><?=$country['users']['country'];?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="data[Paymentmanual][step]" value="1"/>
                                        <?=$this->Form->submit(__('Submit', true), array('class' => 'btn'));?>
                                        <?=$this->Form->end();?>
                                    <?php else: ?>
                                        <?=$this->Form->create();?>
                                        <?=__('Found %d users',count($users));?><br>
                                        <?=__('Please Select Users');?>:
                                        <select type="select" name="data[Paymentmanual][users][]" class="multipleSelect" multiple name="countries">
                                            <?php foreach($users as $user){ ?>
                                                <option value="<?=$user['User']['id'];?>">
                                                    <?=$user['User']['username'];?>: <?=$user[0]['deposit_amount'];?><?=$user['currencies']['currency'];?>(<?=$user[0]['deposit_num'];?>) 
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <a href="javascript://" class="btn btn-warning" onclick="SelectAllValues(document.getElementsByTagName('select')[0])">Select ALL</a><br><br>
                                        <?=__('Amount to Deposit');?>: <input type="text" name="data[Paymentmanual][amount]"/>
                                        <input type="hidden" name="data[Paymentmanual][step]" value="2"/>
                                        <br><br>
                                        <?=$this->Form->submit(__('Fund', true), array('class' => 'btn sbt'));?>
                                        <?=$this->Form->end();?>
                                    <?php endif; ?>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
</div>
<script>
$(".multipleSelect").fastselect();

function SelectAllValues(select) {
  var options = select && select.options;
  var opt;

  for (var i=0, iLen=options.length; i<iLen; i++) {
    opt = options[i];
    opt.selected = true;
  }
  
  $(".sbt").addClass("btn-danger");
}
</script>