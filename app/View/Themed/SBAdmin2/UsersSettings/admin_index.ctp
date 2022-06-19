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
                        <?= $this->Html->link(__('Player View'), ['plugin' => false, 'controller' => 'users', 'action' => 'view', 'prefix' => 'admin', $user_id], ['escape' => false, 'title' => __('Player View')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Custom Player Settings'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Custom Player Settings'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
 
                <table class="table table-bordered table-striped">
                    <tr>
                        <th><?= __('ID'); ?></th>
                        <th><?= __('User'); ?></th>
                        <th><?= __('Key'); ?></th>
                        <th><?= __('Value'); ?></th>
                        <th><?= __('Actions'); ?></th>
                    </tr>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= $row['UserSettings']['id']; ?></td>
                            <td><?= $this->Html->link($row['User']['username'], array('controller' => 'users', 'action' => 'view', $row['UserSettings']['id'])); ?></td>
                            <td><?= $row['UserSettings']['key']; ?></td>
                            <td><?= $row['UserSettings']['value']; ?></td>

                            <?php
                            if ($row['UserSettings']['key'] == 'ticket_limit_by_amount') {
                                $action = 'ticketamounts';
                            } else {
                                if (strpos($row['UserSettings']['key'], ".")) {
                                    $key = explode(".", $row['UserSettings']['key']);
                                    if ($key[0] . '.' . $key[1] == 'limits.league') {
                                        $action = 'ticketleagues';
                                    } else if ($key[0] . '.' . $key[1] == 'limits.sport') {
                                        $action = 'ticketsports';
                                    }
                                } else {
                                    $action = 'risk';
                                }
                            }
                            ?>
                            <td>
                                <?= $this->Html->link(__('Edit', true), array('action' => $action, $row['UserSettings']['user_id']), array('class' => 'btn btn-sm btn-warning')); ?>
                                <?= $this->Html->link(__('Delete', true), array('action' => 'delete', $row['UserSettings']['id'], $row['UserSettings']['user_id']), array('class' => 'btn btn-sm btn-danger'), __('Are you sure?')); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <!--END PAGE CONTENT-->
</div>

