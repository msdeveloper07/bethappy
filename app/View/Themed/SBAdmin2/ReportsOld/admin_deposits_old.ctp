<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Create %s', __($singularName)))))); ?></div>
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
                                        <?=__('Report will show deposits by users and fund and charges by staff. Please see deposits report structure below:');?>
                                        <br><br>
                                        &bull; <?=__('Deposit ID');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(This is deposit ID with consists of numbers.)');?></span> <br>
                                        &bull; <?=__('User ID');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(This is user ID with consists of numbers.)');?></span><br>
                                        &bull; <?=__('User name');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">(<b><?=__('If transaction made by staff it would be blank.');?></b>)</span><br>
                                        &bull; <?=__('Deposit time');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Date and time when deposit was made.)');?></span><br>
                                        &bull; <?=__('Deposit type');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Deposit type with comments.)');?></span><br>
                                        &bull; <?=__('Amount');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Amount of money which deposited or deducted.)');?></span><br>
                                        <br>
                                        <?=__('You can generate ticket report by entering date range below:');?><br><br>
                                        <div style="float:left">
                                            <?= $this->element('reports_form'); ?>
                                        </div>
                                        <?php if (!empty($data)): ?>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <?php foreach ($header as $title): ?>
                                                        <th><?= __($title); ?></th>
                                                    <?php endforeach; ?>
                                                </tr>
                                                <?php foreach ($data as $row): ?>
                                                    <tr>
                                                        <td><?= $row['Deposit']['id']; ?></td>
                                                        <td><?= $row['Deposit']['user_id']; ?></td>
                                                        <td><?= $row['User']['username']; ?></td>
                                                        <td><?= $row['Deposit']['date']; ?></td>
                                                        <td><?= $row['Deposit']['type']; ?></td>
                                                        <td><?= $row['Deposit']['amount']; ?></td>
                                                        <td><?= $row['Deposit']['details']; ?></td>
                                                        <td><?= $row['APCO']['state']; ?></td>
                                                        <td><?= $row['APCO']['CardType']; ?></td>
                                                        <td><?= $row['APCO']['CardCountry']; ?></td>
                                                        <td><?= $row['APCO']['Source']; ?></td>
                                                        <td><?= $row['APCO']['Acq']; ?></td>
                                                        <td><?= $row['APCO']['CardHName']; ?></td>
                                                        <td><?= $row['APCO']['CardExpiry']; ?></td>
                                                        <td><?= $row['APCO']['cardNo']; ?></td>
                                                        <td><?= $row['APCO']['time']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <?= $this->Form->create('Download'); ?>
                                            <?= $this->Form->input('download', array('value' => '1', 'type' => 'hidden')); ?>
                                            <?= $this->Form->input('from', array('type' => 'hidden')); ?>
                                            <?= $this->Form->input('to', array('type' => 'hidden')); ?>
                                            <br>
                                            <?= $this->Form->submit(__('Download', true), array('class' => 'btn btn-danger')); ?>
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