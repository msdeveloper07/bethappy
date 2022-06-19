<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __($singularName))))); ?></div>
    </div>
    <!-- BEGIN PAGE CONTENT-->
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
                                    <?= $this->element('tabs');?>
                                    <div class="tab-content">
                                        <div class="pull-left"><?= $this->element('slots_form');?></div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-content">
                                            <?php if ($data): ?>
                                                <table class="table table-bordered table-striped box">
                                                    <tr>
                                                        <td colspan="7" class="count_tras">
                                                            <span><?=$countdata['bets'] . ' ' . __('Bets');?></span>
                                                            <span><?=$countdata['wins'] . ' ' . __('Wins');?></span>
                                                            <span><?=$countdata['refunds'] . ' ' . __('Refunds');?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?= __('User'); ?></th>
                                                        <th><?= __('Type'); ?></th>
                                                        <th><?= __('Model'); ?></th>
                                                        <th><?= __('Parent ID'); ?></th>
                                                        <th><?= __('Amount'); ?></th>
                                                        <th><?= __('Balance'); ?></th>
                                                        <th><?= __('Date'); ?></th>
                                                    </tr>
                                                    <?php foreach ($data as $row) { ?>
                                                        <tr>
                                                            <td><?=$this->Html->link($row['username'], array('plugin' => false, 'controller' => 'users', 'action' => 'view', $row['user_id']));?></td>
                                                            <td><?=$row['transaction_type'];?></td>
                                                            <td><?=$row['Model'];?></td>
                                                            <td><?=$row['Parent_id'];?></td>
                                                            <td><?=$row['amount'] . ' ' . Configure::read('Settings.currency');?></td>
                                                            <td><?=$row['balance'] . ' ' . Configure::read('Settings.currency');?></td>
                                                            <td><?=$this->Beth->convertDateTime($row['date']);?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                                <?= $this->element('paginator'); ?>
                                            <?php else: ?>
                                                <?=__('No transactions found.');?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

