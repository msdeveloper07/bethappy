<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($singularName), 2 => __('List %s', __($pluralName)))))); ?></div>
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
									
                                         <table class="table table-bordered table-striped" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <th><?=__('User id');?></th>
                                                <th><?=__('Username');?></th>
                                                <th><?=__('Bonus Code');?></th>
                                                <th><?=__('Bonus amount');?></th>
                                                <th><?=__('Bonus Activation Date');?></th>
                                            </tr>
                                            <?php $i = 1; ?>
                                            <?php foreach ($data as $bonusdata): ?>
                                                <?php $class = '';
                                                    if ($i++ % 2 == 0) $class = 'alt'; ?>
                                                    <tr>
                                                        <td><?= $this->Html->link($bonusdata['User']['id'], array('controller' => 'users', 'action' => 'view', $bonusdata['User']['id'])); ?></td>
                                                        <td><?= $bonusdata['User']['username']; ?></td>
                                                        <td><?= $bonusdata['BonusCode']['code']; ?></td>
                                                        <td><?= $bonusdata['BonusCode']['amount']; ?></td>
                                                        <td><?= $this->Beth->convertDate($bonusdata['BonusCodesUser']['activation_date']); ?></td>
                                                    </tr>
                                            <?php endforeach; ?>
                                    </table>
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

