<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Withdraw'), 2 => __('List %s', __('Withdraws')))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget"><div class="widget-body"><?= $this->element('charts/pie', array('placeholderClass' => 'withdraw-charts', 'chartsData' => $chartsData));?></div></div>
            </div>
        </div>
        <hr>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="span12"><?= $this->element('search');?></div>
                        
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">
                                    <?= $this->element('tabs');?>
                                    <div class="tab-content"> 
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="box-content">
                                                    <?php if (!empty($data)): ?>
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th><?=__('ID');?></th>
                                                                    <th><?=__('Date');?></th>
                                                                    <th><?=__('User');?></th>
                                                                    <th><?=__('Type');?></th>
                                                                    <th><?=__('Transaction Target');?></th>
                                                                    <th><?=__('Amount');?></th>
                                                                    <th><?=__('Penalty Amount');?></th>
                                                                    <th><?=__('Status');?></th>
                                                                    <?php if (!empty($actions)){?>
                                                                        <th><?=__('Actions');?></th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($data as $row):?>
                                                                <tr>
                                                                    <td><?= $row['Withdraw']['id'];?></td>
                                                                    <td><?= $this->Beth->convertDateTime($row['Withdraw']['date']);?></td>
                                                                    <td><?= $this->Html->link($row['User']['username'], array('controller'=> 'Users','action' => 'view', $row['User']['id']),array('style' => 'color:' . $field['User']['category_id']));?></td>
                                                                    <td><?= $row['Withdraw']['type'];?></td>
                                                                    <td><?= $row['Withdraw']['transaction_target'];?></td>
                                                                    <td><?= $row['Withdraw']['amount'];?></td>
                                                                    <td><?= $row['Withdraw']['penalty_amount'];?></td>
                                                                    <td><?= $row['Withdraw']['status'];?></td>
                                                                    <?php if (!empty($actions)){?>
                                                                        <td>
                                                                            <?php foreach ($actions as $action) {
                                                                                echo $this->MyHtml->link($action['name'], array('controller' => $action['controller'], 'action' => $action['action'], $row['Withdraw']['id']), array('class' => isset($action['class']) ? $action['class'] : ''), __('Are you sure?')); 
                                                                            } ;?>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    <?php endif;?>
                                                </div>                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>