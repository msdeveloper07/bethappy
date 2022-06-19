<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Sports'), 2 => __('List %s', __('Leagues')))))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                        <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?php echo $this->Html->link(__('Add League', true), array('controller' => 'leagues', 'action' => 'add', $model['Sport']['id']), array('class' => 'btn btn-danger')); ?>
                                        <h2><?php echo $model['Sport']['name'] . ' ' . __('Leagues', true); ?></h2>
                                        <?php if (!empty($data)):
                                            ?>
                                            <table class="table table-bordered table-hover" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th><?php echo $this->Paginator->sort('id'); ?></th>
                                                    <th><?php echo $this->Paginator->sort('name'); ?></th>
                                                    <th><?php echo $this->Paginator->sort('active'); ?></th>
                                                    <th><?php echo __('Actions'); ?> </th>
                                                </tr>
                                                <?php
                                                $i = 1;
                                                foreach ($data as $row):
                                                    $class = null;
                                                    if ($i++ % 2 == 0) {
                                                        $class = ' alt';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="<?php echo $class; ?>"><?php echo $row['League']['id']; ?></td>
                                                        <td class="<?php echo $class; ?>"><?php echo $this->Html->link($row['League']['name'], array('controller' => 'leagues', 'action' => 'view', $row['League']['id'])); ?></td>
                                                        <td class="<?php echo $class; ?>">
                                                            <?php if($row['League']['active'] == 1): ?>
                                                                <?php echo __('Yes'); ?>
                                                            <?php else: ?>
                                                                <?php echo __('No'); ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="actions <?php echo $class; ?>">
                                                            <?php echo $this->Html->link(__('View', true), array('controller' => 'leagues', 'action' => 'view', $row['League']['id']), array('class' => 'btn btn-mini btn-mini')); ?>
                                                            <?php echo $this->Html->link(__('Edit', true), array('controller' => 'leagues', 'action' => 'edit', $row['League']['id']), array('class' => 'btn btn-mini btn-primary')); ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

