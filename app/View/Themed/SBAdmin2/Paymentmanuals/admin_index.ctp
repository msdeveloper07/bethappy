<div class="container-fluid">
    <div class="small-table popbox text-white" id="pop1" data-popbox="pop1"></div>
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Manual Payments'), 2 => __('List %s', __('Manual Payments')))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget"><div class="widget-body"><?= $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $chartsData));?></div></div>
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
                                    <div class="tab-content"> 
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="box-header well">
                                                    <h2> 
                                                        <i class="icon-list-alt"></i>
                                                        <?=__('Manual') . ' ' . $type;?>
                                                    </h2>
                                                    <div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div>
                                                </div>
                                                <div class="box-content">
                                                    <?php if (!empty($data)): ?>
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th><?=__('ID');?></th>
                                                                    <th><?=__('Date');?></th>
                                                                    <th><?=__('User');?></th>
                                                                    <th><?=__('First Name');?></th>
                                                                    <th><?=__('Last Name');?></th>
                                                                    <th><?=__('Email');?></th>
                                                                    <th><?=__('Country');?></th>
                                                                    <th><?=__('Currency');?></th>
                                                                    <th><?=__('Afiiliate');?> <?=__('ID');?></th>
                                                                    <th><?=__('Type');?></th>
                                                                    <th><?=__('Master ID');?></th>
                                                                    <th><?=__('From Target ID');?></th>
                                                                    <th><?= $this->Paginator->sort('amount'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($data as $row):?>
                                                                <tr>
                                                                    <td><a class="paymentinfo" data-id="<?= $row['Paymentmanual']['id'];?>" data-popbox="pop1" data-content="<?=nl2br($row['Paymentmanual']['comment']);?>"><?= $row['Paymentmanual']['id'];?></a></td>
                                                                    <td><?= $this->Beth->convertDateTime($row['Paymentmanual']['date']);?></td>
                                                                    <td><?= $this->Html->link($row['User']['username'], array('plugin'=>false,'controller'=> 'Users','action' => 'view', $row['User']['id']),array('style' => 'color:' . $field['User']['category_id'],'class' => 'popper', 'data-id'=> $row['User']['id'], 'data-popbox' => 'pop1'));?></td>
                                                                    <td><?= $row['User']['first_name'];?></td>
                                                                    <td><?= $row['User']['last_name'];?></td>
                                                                    <td><?= $row['User']['email'];?></td>
                                                                    <td><?= $row['User']['country'];?></td>
                                                                    <td><?= $row['User']['Currency']['name'];?></td>
                                                                    <td><?= $this->Html->link($row['User']['affiliate_id'], array('plugin'=>false,'controller'=> 'Affiliate','action' => 'viewbyid', $row['User']['affiliate_id']),array('style' => 'color:' . $field['User']['category_id'],'class' => 'popper', 'data-id'=> $row['User']['id'], 'data-popbox' => 'pop1'));?></td>
                                                                    <td><?= __($row['Paymentmanual']['type']);?></td>
                                                                    <td><?= $row['Paymentmanual']['master'];?></td>
                                                                    <td><?= $row['Paymentmanual']['from_target'];?></td>
                                                                    <td><?= $row['Paymentmanual']['amount'] . $row['User']['Currency']['code'];?></td>
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