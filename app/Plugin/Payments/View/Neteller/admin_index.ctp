
<div class="container-fluid">
    <div class="small-table popbox text-white" id="pop1" data-popbox="pop1"></div>
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Neteller Payments'), 2 => __('List %s', __('Payments')))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <?php if ($type === 'deposits'):
                        ?>
                        <div class="widget-body">
                            <?= $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $depositsChartsData)); ?>
                        </div>
                    <?php endif;
                    ?>
                    <?php if ($type === 'withdraws'):
                        ?>
                        <div class="widget-body">
                            <?= $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $withdrawsChartsData)); ?>
                        </div>
                    <?php endif;
                    ?>
                </div>
            </div>
        </div>
        <hr>

        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="span12">
                            <?php
                            if ($type === 'deposits') {
                                echo $this->Form->create(false, array('url' => "/admin/payments/neteller/index/deposits", 'type' => 'file', 'id' => 'search-form', 'action' => 'index'));
                            }
                            if ($type === 'withdraws') {
                                echo $this->Form->create(false, array('url' => "/admin/payments/neteller/index/withdraws", 'type' => 'file', 'id' => 'search-form', 'action' => 'index'));
                            }
                            foreach ($search_fields AS $i => &$field) {

                                $kk = str_replace("Neteller.", "", $i);
                                $field['value'] = $search_values['Neteller'][$kk];

                                if (!is_array($field)) {
                                    $search_fields[$i] = array($field);
                                }

                                $class = isset($field['class']) ? $field['class'] : null;

                                $search_fields[$i]['div'] = array('class' => 'search-inputs ' . $class . '');
                                $search_fields[$i]['required'] = false;
                            }

                            echo $this->Form->inputs($search_fields, null, array('fieldset' => false, 'legend' => false));
                            echo $this->Form->button(__('Search', true), array('type' => 'submit', 'id' => 'search_button', 'class' => 'btn btn-primary'));
                            echo $this->Form->end();
                            ?>
                            
                            

                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">

                                    <div class="tab-content"> 
                                        <div class="row-fluid">
                                            <div class="span12">

                                                <div class="box-header well">
                                                    <?php if ($type === 'deposits'): ?>
                                                        <h2> 
                                                            <i class="icon-list-alt"></i>
                                                            <?= __('Deposits List'); ?>
                                                        </h2>
                                                    <?php endif; ?>
                                                    
                                                      <?php if ($type === 'withdraws'): ?>
                                                        <h2> 
                                                            <i class="icon-list-alt"></i>
                                                            <?= __('Withdraws List'); ?>
                                                        </h2>
                                                    <?php endif; ?>
                                                    <div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div>
                                                </div>
                                                <div class="box-content">
                                                    <?php if (!empty($data)): ?>
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th><?= $this->Paginator->sort('Neteller.id'); ?></th>
                                                                    <!--<th><?= $this->Paginator->sort('Type'); ?></th>-->
                                                                    <th><?= $this->Paginator->sort('date'); ?></th>
                                                                    <th><?= $this->Paginator->sort('username'); ?></th>
    <!--                                                                    <th><?= $this->Paginator->sort('first_name'); ?></th>
                                                                    <th><?= $this->Paginator->sort('last_name'); ?></th>-->
    <!--                                                                    <th><?= __('Email'); ?></th>-->
                                                                    <th><?= __('Real'); ?> <?= __('Balance'); ?></th>
                                                                    <th><?= $this->Paginator->sort('amount'); ?></th>
                                                                    <!--<th><?= __('Country'); ?></th>-->
                                                                    <th><?= __('Currency'); ?></th>
                                                                    <th><?= __('Afiiliate'); ?> <?= __('ID'); ?></th>
                                                                    <th><?= __('IP Address'); ?></th>
                                                                    <!--<th><?= __('Token'); ?></th>-->
                                                                    <th><?= __('Remote ID'); ?></th>
                                                                    <th><?= __('Error code'); ?></th>
                                                                    <th><?= __('Error message'); ?></th>
                                                                    <th><?= __('Status'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($data as $row): ?>
                                                                    <tr>
                                                                        <td><a class="paymentinfo" data-id="<?= $row['Neteller']['id']; ?>" data-popbox="pop1" data-content="<?= nl2br($row['Neteller']['logs']); ?>"><?= $row['Neteller']['id']; ?></a></td>
                                                                        <!--<td><?= $row['Neteller']['type']; ?></td>-->
                                                                        <td><?= $this->Beth->convertDateTime($row['Neteller']['date']); ?></td>
                                                                        <td><?= $this->Html->link($row['User']['username'], array('plugin' => false, 'controller' => 'Users', 'action' => 'view', $row['User']['id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $row['User']['id'], 'data-popbox' => 'pop1')); ?></td>
                                                                        <!--<td><?= $row['User']['first_name']; ?></td>
                                                                        <td><?= $row['User']['last_name']; ?></td>
                                                                        <td><?= $row['User']['email']; ?></td>-->
                                                                        <td><?= $row['User']['balance'] . $row['User']['Currency']['code']; ?></td>
                                                                        <td><?= $row['Neteller']['amount'] . $row['User']['Currency']['code']; ?></td>
                                                                        <!--<td><?= $row['User']['country']; ?></td>-->
                                                                        <td><?= $row['User']['Currency']['name']; ?></td>
                                                                        <td><?= $this->Html->link($row['User']['affiliate_id'], array('plugin' => false, 'controller' => 'Affiliate', 'action' => 'viewbyid', $row['User']['affiliate_id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $row['User']['id'], 'data-popbox' => 'pop1')); ?></td>
                                                                        <td><?= $row['Neteller']['ip']; ?></td>
                                                                        <!--<td><?= $row['Neteller']['token']; ?></td>-->
                                                                        <td><?= $row['Neteller']['remote_id']; ?></td>
                                                                        <td><?= $row['Neteller']['errorCode']; ?></td>
                                                                        <td><?= $row['Neteller']['errorMessage']; ?></td>
                                                                        <td>
                                                                            <?= __(array_search($row['Neteller']['status'], Neteller::$humanizeStatuses)); ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                        <?= $this->element('paginator'); ?>
                                                    <?php else: ?>
                                                        <?= _('No data to display.'); ?>
                                                    <?php endif; ?>
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
