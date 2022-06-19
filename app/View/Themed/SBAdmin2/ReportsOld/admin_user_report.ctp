<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __($singularName))))); ?>
            <h3 class="page-title">
                Sum Reports
            </h3>
            <hr>
        </div>
    </div>
    <!-- END PAGE HEADER-->
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
                                    <div class="tab-content">
                                        <div style="float:left">
                                            <?= $this->element('reports_form'); ?>
                                        </div>
                                        <?php if (!empty($data)): ?>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('User ID'); ?></th>
                                                    <th><?= __('User name'); ?></th>
                                                </tr>
                                                <tr>
                                                    <td><?= $data['userId']; ?></td>
                                                    <td><?= $data['username']; ?></td>
                                                </tr>
                                            </table>
                                            <?= $this->Form->create('Download'); ?>
                                            <?= $this->Form->input('download', array('value' => '1', 'type' => 'hidden')); ?>
                                            <?= $this->Form->input('from', array('type' => 'hidden')); ?>
                                            <?= $this->Form->input('to', array('type' => 'hidden')); ?>
                                            <?= $this->Form->submit(__('Download', true), array('class' => 'btn btn-danger', 'div' => false, 'style' => 'margin-top: 15px;')); ?>
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