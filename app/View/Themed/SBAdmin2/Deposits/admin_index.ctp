<div class="container-fluid">
    <div class="small-table popbox text-white" id="pop1" data-popbox="pop1"></div>
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Deposit'), 2 => __('List %s', __('Deposits')))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget"><div class="widget-body"><?= $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $chartsData)); ?></div></div>
            </div>
        </div>
        <hr>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="span12">

                            <?php
                            echo $this->Form->create(false, array('url' => "/admin/deposits/index", 'type' => 'file', 'id' => 'search-form', 'action' => 'index'));


                            foreach ($search_fields AS $i => &$field) {

                                $kk = str_replace("Skrill.", "", $i);
                                $field['value'] = $search_values['Skrill'][$kk];

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
                                    <?= $this->element('tabs'); ?>
                                    <div class="tab-content"> 
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="box-header well">
                                                    <h2><i class="icon-list-alt"></i><?= __('Deposits'); ?></h2>
                                                    <div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div>
                                                </div>
                                                <div class="box-content">
                                                    <?php if (!empty($data)): ?>
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th><?= __('ID'); ?></th>
                                                                    <th><?= __('Date'); ?></th>
                                                                    <th><?= __('User'); ?></th>
                                                                    <th><?= __('Method'); ?></th>
                                                                    <th><?= __('Amount'); ?></th>
                                                                     <th><?= __('Currency'); ?></th>
                                                                    <th><?= __('Parent ID'); ?></th>
                                                                    <th><?= __('Status'); ?></th>
                                                                    <?php if (!empty($actions)) { ?>
                                                                        <th><?= __('Actions'); ?></th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($data as $row): ?>
                                                                    <tr>
                                                                        <td><?= $row['Deposit']['id']; ?></td>
                                                                        <td><?= $this->Beth->convertDateTime($row['Deposit']['date']); ?></td>
                                                                        <td><?= $this->Html->link($row['User']['username'], array('controller' => 'Users', 'action' => 'view', $row['User']['id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $row['User']['id'], 'data-popbox' => 'pop1')); ?></td>
                                                                        <td><?= $row['Deposit']['model']; ?></td>
                                                                        <td><?= $row['Deposit']['amount']; ?></td>
                                                                         <td><?= $row['Deposit']['currency']; ?></td>
                                                                        <td><?= $row['Deposit']['parent_id']; ?></td>
                                                                        <td><?= __($row['Deposit']['status']); ?> <i class="icon-info-sign paymentinfo" data-popbox="pop1" data-id="<?= $row['Deposit']['id']; ?>" data-content="<?= nl2br($row['Deposit']['details']); ?>"></i></td>
                                                                            <?php if (!empty($actions)) { ?>
                                                                            <td>
                                                                                <?php
                                                                                foreach ($actions as $action) {
                                                                                    echo $this->MyHtml->link($action['name'], array('controller' => $action['controller'], 'action' => $action['action'], $row['Deposit']['id']), array('class' => isset($action['class']) ? $action['class'] : ''), __('Are you sure?'));
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                    <?php } ?>
                                                                    </tr>
    <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
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