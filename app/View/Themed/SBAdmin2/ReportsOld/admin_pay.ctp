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
                                    <div class="tab-content">
                                        <?=__('You can generate ticket report by entering date range below:');?><br><br>
                                        <div>
                                            <?= $this->element('reports_form'); ?>
                                        </div>
                                        <?=date("d/m/Y H:i:s",strtotime($from));?> - <?=date("d/m/Y H:i:s",strtotime($to));?>
                                        <?php if (!empty($data)): ?>
                                        <?php foreach($data as $key=>$trans):?>
                                        <h2><?=$key;?></h2>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <?php foreach($trans as $key=>$data):?>
                                                <th><?= __($key); ?></th>
                                                <?php endforeach;?>
                                            </tr>
                                            <tr>
                                                <?php foreach($trans as $key=>$data):?>
                                                <td><?= abs($data); ?></td>
                                                <?php endforeach;?>
                                            </tr>
                                        </table>
                                        <?php endforeach;?>
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