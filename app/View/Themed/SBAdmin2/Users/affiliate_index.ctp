<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Players'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Players'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('search'); ?><br/>
            </div>
            <div class="col-md-12">
                <div class="table-responsive-sm pt-2">
                    <?php if (!empty($data)): ?>
                        <?php
                        // break url to pass as param
                        $split = explode('/', $this->request->here);
                        $here = implode('_', $split);
                        $here = str_replace(":", "#", $here);
                        ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="text-align: center"><?= $this->Paginator->sort('ID'); ?></th>
                                    <th><?= $this->Paginator->sort('User.username'); ?></th>
                                    <th><?= $this->Paginator->sort('User.first_name'); ?></th>
                                    <th><?= $this->Paginator->sort('User.last_name'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('Country'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('Affiliate ID'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('Status'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('Login Status'); ?></th>
                                    <th><?= $this->Paginator->sort('e-Mail'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('KYC Status'); ?></th>
                                    <th style="text-align: right"><?= $this->Paginator->sort('balance'); ?></th>
                                    <th><?= $this->Paginator->sort('registration_date'); ?></th>
<!--                                    <th><?= $this->Paginator->sort('Registration IP'); ?></th>
                                    <th><?= $this->Paginator->sort('Last Visit IP'); ?></th>-->
                                    <th style="text-align: center;"><?= __('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $field): ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $field['User']['id']; ?></td>
                                        <td>
                                            <?php
                                            if ($field['User']['category_id'] != '') {
                                                echo $this->Html->link($field['User']['username'], array('action' => 'view', $field['User']['id']), array('style' => 'font-weight:bold; color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $field['User']['id'], 'data-popbox' => 'pop1'));
                                            } else {
                                                echo $this->Html->link($field['User']['username'], array('action' => 'view', $field['User']['id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $field['User']['id'], 'data-popbox' => 'pop1'));
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align: center;"><?= $field['User']['first_name']; ?></td>
                                        <td style="text-align: center;"><?= $field['User']['last_name']; ?></td>
                                        <td style="text-align: center;"><?= $field['User']['country']; ?></td>
                                        <td style="text-align: center;"><?= $field['User']['affiliate_id']; ?></td>
                                        <td style="text-align: center;"><?= $field['User']['status']; ?></td>
                                        <td style="text-align: center;"><?= $field['User']['login_status']; ?></td>
                                        <td><?= $field['User']['email']; ?></td>
                                        <td style="text-align: center;">
                                            <?php
                                            switch ($field['User']['kyc_status']) {
                                                case '-1':
                                                    $kyc = 'DISCARD';
                                                    $color = 'red';
                                                    break;

                                                case '0':
                                                    $kyc = 'PENDING';
                                                    $color = '';
                                                    break;

                                                case '1':
                                                    $kyc = 'ADDRESS';
                                                    $color = '#f6c23e';
                                                    break;

                                                case '2':
                                                    $kyc = 'FUNDING';
                                                    $color = 'orange';
                                                    break;

                                                case '3':
                                                    $kyc = 'PERSONAL INFO';
                                                    $color = 'green';
                                                    break;
                                            }

                                            echo $this->Html->link($kyc, array('controller' => 'users', 'action' => 'kyc', $field['User']['id']), array('style' => 'font-size:11px;font-weight:700;color:' . $color));
                                            ?>
                                        </td>
                                        <td style="text-align: right;"><?= $field['User']['balance'] ?></td>
                                        <td><?= date('d-m-Y H:i:s', strtotime($field['User']['registration_date'])); ?></td>
<!--                                        <td><?= $this->Html->link($field['User']['ip'], array('controller' => 'users', 'action' => 'admin_user', $field['User']['ip'])); ?></td>
                                        <td><?= $this->Html->link($field['User']['last_visit_ip'], array('controller' => 'users', 'action' => 'admin_user', $field['User']['last_visit_ip'])); ?></td>-->
                                 
                                        <td style="min-width: 190px;text-align: center;" class="actions">
                                            <?php
                                            foreach ($actions as $action) {
                                                echo $this->Html->link($action['name'], array('plugin' => $action['plugin'],'controller' => $action['controller'], 'action' => $action['action'], $field['User']['id']), array('class' => isset($action['class']) ? $action['class'] : ''));
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?= $this->element('paginator'); ?>
                    <?php else: ?>
                        <p><?= __('No records found'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="small-table popbox" id="pop1" data-popbox="pop1"></div>
