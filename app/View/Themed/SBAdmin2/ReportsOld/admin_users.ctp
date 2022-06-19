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
                                        <?=__("Report will show only users which are registered trough registration form. Please see user report structure:");?><br><br>

                                        &bull; <?=__('User ID');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(This is user ID with consists of numbers.)');?></span><br>
                                        &bull; <?=__('Date of registration');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Date and time then user was registered in website.)');?></span><br>
                                        &bull; <?=__('Username');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Username which was entered then registration was made.)');?></span><br>
                                        &bull; <?=__('First name');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(First name of user.)');?></span><br>
                                        &bull; <?=__('Last name');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Last name of user.)');?></span><br>
                                        &bull; <?=__('Country');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Code of country which was selected in user registration.)');?></span><br>
                                        <br>

                                        <?=__('You can generate user report by entering date range below:');?><br><br>
                                        <div style="float:left">
                                            <?= $this->element('reports_form'); ?>
                                        </div>
                                        <?php if (!empty($data)): ?>
                                            <table class="table table-custom">
                                                <tr>
                                                    <th><?= __('User ID'); ?></th>
                                                    <th><?= __('Date of registration'); ?></th>
                                                    <th><?= __('Username'); ?></th>
                                                    <th><?= __('Current balance'); ?></th>
                                                    <th><?= __('First name'); ?></th>
                                                    <th><?= __('Last name'); ?></th>
                                                    <th><?= __('Country'); ?></th>
                                                </tr>
                                                <?php foreach ($data as $row): ?>
                                                    <tr>
                                                        <td><?= $row['User']['id']; ?></td>
                                                        <td><?= $this->Beth->convertDate($row['User']['registration_date']); ?></td>
                                                        <td><?= $row['User']['username']; ?></td>
                                                        <td><?= $row['User']['balance']; ?></td>
                                                        <td><?= $row['User']['first_name']; ?></td>
                                                        <td><?= $row['User']['last_name']; ?></td>
                                                        <td><?= $row['User']['country']; ?></td>
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