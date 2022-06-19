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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Player Notes'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Player Notes'); ?></h1>
            </div>
            <br>
        </div>

    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <!--            <div class="col-md-12">
                            <?//= $this->element('user_tabs'); ?>
                        </div>-->
            <div class="col-md-12 pt-2">




                <?php if (!empty($data)): ?>
                    <?php
                    ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="text-align: center"><?= $this->Paginator->sort('ID'); ?></th>
                                <th><?= $this->Paginator->sort('User'); ?></th>
                                <th><?= $this->Paginator->sort('Content'); ?></th>
                                <th><?= $this->Paginator->sort('Submitted By'); ?></th>
                                <th><?= $this->Paginator->sort('Created'); ?></th>
                                <th><?= $this->Paginator->sort('Modified'); ?></th>

                                        <!--<th style="text-align: center;"><?//= __('Actions'); ?></th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $field): ?>
                                <tr>
                                    <td><?= $field['Note']['id']; ?></td>
                                    <td><?= $this->Html->link($field['User']['username'], array('controller' => 'users', 'action' => 'view', $field['User']['id'])); ?></td>
                                    <td><?= $field['Note']['content']; ?></td>
                                    <td><?= $this->Html->link($field['Author']['username'], array('controller' => 'users', 'action' => 'view', $field['User']['id'])); ?></td>
                                    <td><?= date('d-m-Y H:i:s', strtotime($field['Note']['created'])); ?></td>
                                    <td><?= date('d-m-Y H:i:s', strtotime($field['Note']['modified'])); ?></td>


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
