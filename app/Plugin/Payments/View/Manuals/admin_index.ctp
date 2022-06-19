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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Manual'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Manual'); ?></h1>
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
                <?= $this->element('search'); ?> 

                <br/>

                <div class="table-responsive">
                    <?= $this->element('tabs'); ?>
                    <?php if (!empty($data)): ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?= __('ID'); ?></th>

                                    <th><?= __('User'); ?></th>
                                    <th><?= __('Method'); ?></th>
                                    <th><?= $this->Paginator->sort('amount'); ?></th>

                                    <th><?= __('Currency'); ?></th>
                                    <th><?= __('Afiiliate'); ?> <?= __('ID'); ?></th>

                                    <th><?= __('Master ID'); ?></th>
                                    <th><?= __('From Target ID'); ?></th>
                                    <th><?= __('Date'); ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr>
                                        <td><a class="paymentinfo" data-id="<?= $row['Manual']['id']; ?>" data-popbox="pop1" data-content="<?= nl2br($row['Manual']['comment']); ?>"><?= $row['Manual']['id']; ?></a></td>
                                        <td><?= $this->Html->link($row['User']['username'], array('plugin' => false, 'controller' => 'Users', 'action' => 'view', $row['User']['id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $row['User']['id'], 'data-popbox' => 'pop1')); ?></td>
                                        <td><?= $row['Manual']['method']; ?></td>
                                        <td><?= $row['Manual']['amount'] . $row['User']['Currency']['code']; ?></td>
                                        <td><?= $row['User']['Currency']['name']; ?></td>
                                        <td><?= $this->Html->link($row['User']['affiliate_id'], array('plugin' => false, 'controller' => 'Affiliate', 'action' => 'viewbyid', $row['User']['affiliate_id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $row['User']['id'], 'data-popbox' => 'pop1')); ?></td>
                                        <td><?= $row['Manual']['master']; ?></td>
                                        <td><?= $row['Manual']['from_target']; ?></td>
                                        <td><?= date('d-m-Y H:i:s', strtotime($row['Manual']['date'])); ?></td>

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
