<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><h3 class="page-title"><?= __('$pluralName');?></h3></div>
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
                                        <?= $this->element('reports_form'); ?>

                                        <?php if (!empty($data)): ?>
                                            <?php foreach ($data as $report): ?>
                                                <table class="table table-bordered table-striped">
                                                    <tr>
                                                        <?php foreach ($report['header'] as $title): ?>
                                                            <th><?= __($title); ?></th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    <?php foreach ($report['data'] as $row): ?>
                                                        <tr>
                                                            <?php foreach ($row as $field): ?>
                                                                <td><?= $field; ?></td>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>

                                            <?php endforeach; ?>

                                            <?= $this->Form->create('Download'); ?>
                                            <?= $this->Form->input('download', array('value' => '1', 'type' => 'hidden')); ?>
                                            <?= $this->Form->input('from', array('type' => 'hidden')); ?>
                                            <?= $this->Form->input('to', array('type' => 'hidden')); ?>
                                            <?= $this->Form->submit(__('Download', true), array('class' => 'btn btn-danger', 'div' => false)); ?>
                                            <?= $this->Form->end(); ?>

                                        <?php elseif (isset($data)): ?>
                                            <?= __('No data in this period'); ?>
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