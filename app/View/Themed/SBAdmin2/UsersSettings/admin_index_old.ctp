<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">            
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __($singularName))))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">
                                    <?= $this->element('tabs');?>
                                    <div class="tab-content">
                                        
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?= __('ID'); ?></th>
                                                <th><?= __('User'); ?></th>
                                                <th><?= __('Key');?></th>
                                                <th><?= __('Value'); ?></th>
                                                <th><?= __('Actions');?></th>
                                            </tr>
                                            <?php foreach ($data as $row): ?>
                                                <tr>
                                                    <td><?=$row['Userssetting']['id'];?></td>
                                                    <td><?=$this->Html->link($row['User']['username'], array('controller' => 'users', 'action' => 'view', $row['Userssetting']['id']));?></td>
                                                    <td><?=$row['Userssetting']['key'];?></td>
                                                    <td><?=$row['Userssetting']['value'];?></td>
                                                    
                                                    <?php 
                                                        if ($row['Userssetting']['key'] == 'ticket_limit_by_amount') {
                                                            $action = 'ticketamounts';
                                                        } else {
                                                            if (strpos($row['Userssetting']['key'], ".")) {
                                                                $key = explode(".", $row['Userssetting']['key']);
                                                                if ($key[0].'.'.$key[1] == 'limits.league') {
                                                                    $action = 'ticketleagues';
                                                                } else if ($key[0].'.'.$key[1] == 'limits.sport') {
                                                                    $action = 'ticketsports';
                                                                }
                                                            } else {
                                                                $action = 'risk';
                                                            }
                                                        } ?>
                                                    <td>
                                                        <?=$this->Html->link(__('Edit', true), array('action' => $action, $row['Userssetting']['user_id']), array('class' => 'btn btn-small btn-primary'));?>
                                                        <?=$this->Html->link(__('Delete', true), array('action' => 'delete', $row['Userssetting']['id'], $row['Userssetting']['user_id']), array('class' => 'btn btn-small btn-danger'),__('Are you sure?'));?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>