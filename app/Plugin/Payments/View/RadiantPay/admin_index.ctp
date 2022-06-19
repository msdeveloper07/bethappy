<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Payments'); ?></li>
                    <li class="breadcrumb-item"><?= __('Deposits'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Radiant Pay'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Radiant Pay'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="widget"><div class="widget-body"><?= $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $chartsData)); ?></div></div>
                <br/>


                <?php
                echo $this->Form->create(false, array('url' => "/admin/payments/RadiantPay", 'type' => 'file', 'id' => 'search-form', 'action' => 'index'));

                foreach ($search_fields AS $i => &$field) {

                    $kk = str_replace("RadiantPay.", "", $i);
                    $field['value'] = $search_values['RadiantPay'][$kk];

                    if (!is_array($field)) {
                        $search_fields[$i] = array($field);
                    }
//                    var_dump($search_fields[$i]);
                    //$class = isset($field['class']) ? $field['class'] : null;

                    $search_fields[$i]['div'] = array('class' => 'form-group mr-2');
                    $search_fields[$i]['required'] = false;
                }
                ?>
                <div class="form-row align-items-center justify-content-flex-start flex-wrap">
                    <?php echo $this->Form->inputs($search_fields, null, array('fieldset' => false, 'legend' => false,));
                    ?>
                </div>
                <?php
                echo $this->Form->button(__('Search', true), array('type' => 'submit', 'id' => 'search_button', 'class' => 'btn btn-primary'));
                echo $this->Form->end();
                ?>		

                <br/>

                <div class="table-responsive">
                    <?= $this->element('tabs'); ?>
                    <?php if (!empty($data)): ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?= $this->Paginator->sort('id'); ?></th>
                                    <th><?= $this->Paginator->sort('username', 'Player'); ?></th>
                                    <th><?= $this->Paginator->sort('amount'); ?></th>
                                    <th><?= __('Currency'); ?></th>
                                    <th><?= $this->Paginator->sort('amount_in_usd', 'Amount in USD'); ?></th>
                                    <th><?= __('Afiiliate'); ?> <?= __('ID'); ?></th>
                                    <th><?= __('Remote ID'); ?></th>
                                    <th><?= __('IP Address'); ?></th>
                                    <th><?= __('Date'); ?></th>
                                    <th><?= __('Error message'); ?></th>
                                    <th><?= __('Status'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr>
                                        <td><a class="paymentinfo" data-id="<?= $row['RadiantPay']['id']; ?>" data-popbox="pop1" data-content="<?= nl2br($row['RadiantPay']['logs']); ?>"><?= $row['RadiantPay']['id']; ?></a></td>
                                        <td><?= $this->Html->link($row['User']['username'], array('plugin' => false, 'controller' => 'Users', 'action' => 'view', $row['User']['id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $row['User']['id'], 'data-popbox' => 'pop1')); ?></td>
                                        <td><?= $row['RadiantPay']['amount'] . $row['User']['Currency']['code']; ?></td>
                                        <td><?= $row['RadiantPay']['currency']; ?></td>
                                        <td><?= $row['RadiantPay']['amount_in_usd']; ?></td>
                                        <td><?= $this->Html->link($row['User']['affiliate_id'], array('plugin' => false, 'controller' => 'Affiliate', 'action' => 'viewbyid', $row['User']['affiliate_id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $row['User']['id'], 'data-popbox' => 'pop1')); ?></td>
                                        <td><?= $row['RadiantPay']['remote_id']; ?></td>
                                        <td><?= $row['RadiantPay']['ip']; ?></td>
                                        <td><?= date("d-m-Y H:i:s", strtotime($row['RadiantPay']['date'])); ?></td>
                                        <td><?= $row['RadiantPay']['error_mesage']; ?></td>
                                        <td>
                                            <?= $this->element('status_button', array('status' => __(array_search($row['RadiantPay']['status'], RadiantPay::$humanizeStatuses)))); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <?= $this->element('paginator'); ?>
                </div>
            </div>
        </div>
    </div>
</div>