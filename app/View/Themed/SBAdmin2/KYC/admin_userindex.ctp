
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Players'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Players List'), ['plugin' => false, 'controller' => 'users', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Players List')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Player View'), ['plugin' => false, 'controller' => 'users', 'action' => 'view', 'prefix' => 'admin', $user_id], ['escape' => false, 'title' => __('Players List')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('KYC (Know Your Customer)'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('KYC (Know Your Customer)'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12 pt-2">
                <?php if (!empty($data)): ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?= __('ID'); ?></th>
                                <th><?= __('User'); ?></th>
                                <th><?= __('KYC Type'); ?></th>
                                <th><?= __('File Type'); ?></th>
                                <th><?= __('KYC Data URL'); ?></th>
                                <th><?= __('Status'); ?></th>
                                <th><?= __('Reason'); ?></th>
                                <th><?= __('Created'); ?></th>
                                <th><?= __('Expires'); ?></th>
                                <th><?= __('Actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $row): ?>
                                <tr>
                                    <td><?= $row['KYC']['id']; ?></td>
                                    <td><?= $this->Html->link($row['KYC']['username'], ['plugin' => false, 'controller' => 'users', 'action' => 'view', 'prefix' => 'admin', $user_id], ['escape' => false]); ?></td>
                                    <td>
                                        <?php
                                        switch ($row['KYC']['kyc_type']) {
                                            case 1:
                                                echo '<button class="btn btn-sm btn-outline-primary btn-table">'.__('IDENTIFICATION').'</button>';
                                                break;

                                            case 2:
                                                echo '<button class="btn btn-sm btn-outline-info btn-table">'.__('ADDRESS').'</button>';
                                                break;

                                            case 3:
                                                echo '<button class="btn btn-sm btn-outline-dark btn-table">'.__('FUNDING').'</button>';
                                                break;
                                        }
                                        ?></td>
                                    <td><?= $row['KYC']['file_type']; ?></td>
                                    <td><?= $row['KYC']['kyc_data_url']; ?></td>
                                    <td>
                                        <?php
                                        switch ($row['KYC']['status']) {
                                            case 1:
                                                echo '<button class="btn btn-sm btn-success btn-table">'.__('Approved').'</button>';
                                                break;

                                            case 0:
                                                echo '<button class="btn btn-sm btn-warning btn-table">'.__('Pending').'</button>';
                                                break;

                                            case -1:
                                                echo '<button class="btn btn-sm btn-danger btn-table">'.__('Rejected').'</button>';
                                                break;
                                        }
                                        ?></td>
                                    <td><?= $row['KYC']['reason']; ?></td>
                                    <td><?= date("d-m-Y H:i:s", strtotime($row['KYC']['created'])); ?></td>
                                    <td><?= $row['KYC']['expires'] != NULL ? date("d-m-Y H:i:s", strtotime($row['KYC']['expires'])) : ''; ?></td>

                                    <td>  <?php
                                        foreach ($actions as $action) {
                                            echo $this->Html->link($action['name'], array('action' => $action['action'], $row['KYC']['id']), array('class' => isset($action['class']) ? $action['class'] : ''));
                                        }
                                        ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
