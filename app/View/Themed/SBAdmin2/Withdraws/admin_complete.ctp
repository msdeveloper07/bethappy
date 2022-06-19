<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($singularName), 2 => __('List %s', __($pluralName)))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
                                        <h3 style="text-align:center"><?=__('Last Deposit APCO Information');?></h3>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?= __('APCO ID'); ?></th>
                                                    <th><?= __('APCO Transaction ID');?></th>
                                                    <th><?= __('APCO Card Type');?></th>
                                                    <th><?= __('APCO Source');?></th>
                                                    <th><?= __('APCO Card HName');?></th>
                                                    <th><?= __('APCO No');?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php //echo $last_deposit_apco_details['Apcos']['id']; ?></td>
                                                    <td><?php //echo $last_deposit_apco_details['Apcos']['transaction_id']; ?></td>
                                                    <td><?php //echo $last_deposit_apco_details['Apcos']['CardType']; ?></td>
                                                    <td><?php //echo $last_deposit_apco_details['Apcos']['Source']; ?></td>
                                                    <td><?php //echo $last_deposit_apco_details['Apcos']['CardHName']; ?></td>
                                                    <td><?php //echo $last_deposit_apco_details['Apcos']['cardNo']; ?></td>
                                                </tr> 
                                            </tbody>
                                        </table>
                                        <br><br>
                                        <h2 style="text-align:center"><?=__('Complete Withdraw');?></h2>
					<hr/>
                                        <span style="text-align:center">
                                            <?= $this->Form->create(null, array('url' => $submitUrl,'target'=>'iff')); ?>
                                            <?php if(isset($fields) AND is_array($fields)): ?>
                                                <?php foreach($fields AS $fieldName => $fieldValue): ?>
                                                    <?= $this->Form->input(null, array('name' => $fieldName, 'value' => $fieldValue, 'type' => 'hidden')); ?>
                                                    <?= $this->Form->submit();?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?= $this->Form->end(); ?>
                                        </span>
                                        <br>
                                        <iframe style="display: none;" src="" width="550" height="400" name="iff" frameborder="0"></iframe>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var COUNTER = 0;
    
    $(document.body).on("click", '.submit', function(e) {
        $('.tab-content').append('<div id="pending">Waiting for Response <b>.</b></div>');
        
        check();
        
        function check() {
            COUNTER++;
            
            if(COUNTER == 20) {
                $('#pending').text("Request timed out, please check again later.");
            }
            
            $.ajax({
                url:     "/admin/withdraws/getanswer/<?= $withdraw_id; ?>",
                success: function(data) {
                    try {
                        data = JSON.parse(data);
                        
                        var $_wait = $('#pending');
                        
                        // check withdraw status every second until it 
                        // changes from pending
                        if(data.Withdraw.status == "pending") {
                            if($_wait.children('b').text() == "...") {
                                $_wait.children('b').html('.');
                            }
                            else {                            
                                $_wait.children('b').append('.');
                            }

                            setTimeout(check, 1000);
                        }
                        else {
                            $_wait.text("Request " + data.Withdraw.status);
                        }
                    }
                    catch(ex) {
                        console.log(ex);
                        setTimeout(check, 1000);
                    }
                }
            });
        }
    });
</script>