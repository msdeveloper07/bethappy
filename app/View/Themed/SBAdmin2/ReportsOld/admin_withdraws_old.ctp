<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Create %s', __($singularName)))))); ?></div>
    </div>
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <?= $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?= $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?=__('Report will show withdraw requests. Please see withdraws report structure below:');?><br><br>

                                        &bull; <?=__('Withdraw ID');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(This is withdraw ID with consists of numbers.)');?></span> <br>
                                        &bull; <?=__('User ID');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(This is user ID with consists of numbers.)');?></span><br>
                                        &bull; <?=__('User name');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Name of user in the system.)');?></span><br>
                                        &bull; <?=__('First last name');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__("(Full name of user.)");?></span><br>
                                        &bull; <?=__('Bank name');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__("(User's bank name.)");?></span><br>
                                        &bull; <?=__('Bank code');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Code of bank.)');?></span><br>
                                        &bull; <?=__('Account no.');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Bank account number.)');?></span><br>
                                        &bull; <?=__('Request time');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Time when withdraw was requested.)');?></span><br>
                                        &bull; <?=__('Withdraw type');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Type of withdraw request.)');?></span><br>
                                        &bull; <?=__('Amount');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(Amount of money which was request by user to withdraw.)');?></span><br>
                                        <br>

                                        <?=_('You can generate ticket report by entering date range below:');?><br><br>
                                        
                                        <div style="float:left"><?= $this->element('reports_form'); ?></div>
                                        <?php if (!empty($data)): ?>

                                            <table class="table table-custom">
                                                <tr>
                                                    <?php foreach ($header as $title): ?>
                                                        <th><?= __($title); ?></th>
                                                    <?php endforeach; ?>
                                                </tr>
                                                <?php foreach ($data as $row): ?>
                                                    <tr>
                                                        <td><?= $row['Withdraw']['id']; ?></td>
                                                        <td><?= $row['Withdraw']['user_id']; ?></td>
                                                        <td><?= $row['User']['username']; ?></td>
                                                        <td><?= $row['User']['first_name'] . ' ' . $row['User']['last_name'] ?></td>
                                                        <td><?= $row['User']['bank_name'] ?></td>
                                                        <td><?= $row['User']['bank_code'] ?></td>
                                                        <td><?= $row['User']['account_number'] ?></td>
                                                        <td><?= $this->Beth->convertDate($row['Withdraw']['date']); ?></td>
                                                        <td><?= $row['Withdraw']['type']; ?></td>
                                                        <td><?= $row['Withdraw']['amount']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <?= $this->Form->create('Download'); ?>
                                            <?= $this->Form->input('download', array('value' => '1', 'type' => 'hidden')); ?>
                                            <?= $this->Form->input('from', array('type' => 'hidden')); ?>
                                            <?= $this->Form->input('to', array('type' => 'hidden')); ?>
                                            <br>
                                            <?= $this->Form->submit(__('Download', true), array('class' => 'btn btn-danger', 'div' => false)); ?>
                                            <?= $this->Form->end(); ?>
                                        <?php elseif (isset($data)): ?>
                                            <?= __('<br>No data in this period'); ?>
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
    <!-- END PAGE CONTENT-->
</div>
