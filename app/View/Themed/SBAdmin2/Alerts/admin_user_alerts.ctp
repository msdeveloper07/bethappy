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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Player Alerts'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Player Alerts'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">

                <div class="table-responsive-sm">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                <?php
//                $type = $this->Paginator->sortDir() === 'asc' ? 'up' : 'down';
//                $icon = "<i class='fas fa-sort-" . $type . "'></i>";
                ?>
                                <th><?= $this->Paginator->sort('id'); ?></th>
                                <th><?= $this->Paginator->sort('User.username', __('User'), array('escape' => false)); ?></th>
                                <th><?= $this->Paginator->sort('alert_source', __('Source')); ?></th>
                                <th><?= $this->Paginator->sort('alert_model', __('Model')); ?></th>
                                <th><?= $this->Paginator->sort('alert_text', __('Message')); ?></th>
                                <th><?= $this->Paginator->sort('date'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data)): ?>
                                <?php foreach ($data as $row) { ?>
                                    <tr>
                                        <td><?= $row['Alert']['id']; ?></td>
                                        <td><?= $this->Html->link($row['User']['username'], array('controller' => 'users', 'action' => 'view', $row['Alert']['user_id'])); ?></td>
                                        <td><?= $row['Alert']['alert_source']; ?></td>
                                        <td><?= $row['Alert']['alert_model']; ?></td>
                                        <td><?= $row['Alert']['alert_text']; ?></td>
                                        <td><?= date("d-m-Y H:i:s", strtotime($row['Alert']['date'])); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6"><?= __("No data to display."); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                echo $this->Paginator->counter(array(
                    'format' => __('Page {:page} / {:pages}, showing {:start} - {:end} of {:count} total records')
                ));

                if ($this->Paginator->hasPage(2)):
                    ?>
                    <div class="paging">
                        <?php echo $this->Paginator->first('<i class="icon ion-ios-arrow-left"></i>', array('class' => 'disabled', 'escape' => false)); ?>

                        <?php if ($this->Paginator->hasPrev()): ?>
                            <?php echo $this->Paginator->prev('<i class="icon ion-ios-arrow-thin-left"></i>', array('escape' => false), null, array('class' => 'disabled')); ?>
                        <?php endif; ?>

                        <?php echo $this->Paginator->numbers(array('separator'=>' ')); ?>

                        <?php if ($this->Paginator->hasNext()): ?>
                            <?php echo $this->Paginator->next('<i class="icon ion-ios-arrow-thin-right"></i>', array('escape' => false), null, array('class' => 'disabled')) . "\n"; ?>
                        <?php endif; ?>

                        <?php echo $this->Paginator->last('<i class="icon ion-ios-arrow-right"></i>', array('escape' => false, 'class' => 'disabled')); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--END PAGE CONTENT-->
</div>