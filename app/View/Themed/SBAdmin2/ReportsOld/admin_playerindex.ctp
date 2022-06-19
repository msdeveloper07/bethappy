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
                                        <?=__('Report will show only users which are registered trough registration form.');?>
                                        <br><br>
                                        <?= $this->MyHtml->link('Total Liability Report', array('controller' => 'Reports', 'action' => 'playerliabilityreport'), array('class' => 'btn btn-mini btn-primary'));?>
                                        
                                        <br /><br><br>
                                        <?php if (!empty($data)): ?>
                                            <table class="table table-custom">
                                                <tr>
                                                    <th><?= __('User ID'); ?></th>
                                                    <th><?= __('Username'); ?></th>
                                                    <th><?= __('First name'); ?></th>
                                                    <th><?= __('Last name'); ?></th>
                                                    <th><?= __('Country'); ?></th>
                                                    <th><?= __('Actions'); ?></th>
                                                </tr>
                                                <?php foreach ($data as $row): ?>
                                                    <tr>
                                                        <td><?= $row['User']['id']; ?></td>
                                                        <td><?= $row['User']['username']; ?></td>
                                                        <td><?= $row['User']['first_name']; ?></td>
                                                        <td><?= $row['User']['last_name']; ?></td>
                                                        <td><?= $row['User']['country']; ?></td>
                                                        <td><?= $this->MyHtml->link('Liability Report', array('controller' => 'Reports', 'action' => 'playerliabilityreport', $row['User']['id']), array('class' => 'btn btn-mini btn-primary'));?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?= $this->element('paginator'); ?>
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