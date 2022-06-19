<div class="container-fluid">
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                               <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">	                                        
                                        <div class="span6">
                                            <h5><?=__('You can generate an alert report by entering date range below:');?></h5>							
                                            <?= $this->element('reports_form');?>
                                            <br />
                                        </div>
                                        
                                        <div class="span6">
                                            <h4><?=__('Alert:');?></h4>
                                            &bull; <?=__('New Withdraws Alert');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Alert when user makes new withdraw request.)');?></span><br>
					</div>
                                        
                                        <?php if (!empty($data)): ?>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Alert ID'); ?></th>
                                                    <th><?= __('User id'); ?></th>
                                                    <th><?= __('Alert Title'); ?></th>
                                                    <th><?= __('Alert'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                </tr>
                                                <?php foreach ($data as $field) { ?>
                                                    <tr>
                                                        <td><?= $field['Alert']['id']; ?></td>
                                                        <td><?= $this->Html->link($field['User']['username'], array('controller'=>'withdraws', 'action'=>'admin_user', $field['User']['id'])); ?></td>
                                                        <td><?= $field['Alert']['alert_source']; ?></td>
                                                        <td><?= $field['Alert']['alert_text']; ?></td>
                                                        <td><?= $this->Beth->convertDate($field['Alert']['date']);?></td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        <?php endif; ?>
                                    </div>
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