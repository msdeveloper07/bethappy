
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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Player Casino Log'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Player Casino Log'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('reports_form'); ?><br/>
                <?php if (!empty($data)): ?>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th><?= __('ID'); ?></th>
                            <th><?= __('Provider'); ?></th>
                            <th><?= __('Transaction Type'); ?></th>
                            <th><?= __('Amount'); ?></th>
                            <th><?= __('Balance'); ?></th> 

                            <th><?= __('Date'); ?></th>


                        </tr>
                        <?php foreach ($data as $row) { ?>
                            <tr>
                                <td><?= $row['TransactionLog']['id']; ?></td>        
                                <td><?= $row['TransactionLog']['provider']; ?></td> 
                                <td><?= __($row['TransactionLog']['transaction_type']); ?></td>
                                <td><?= $row['TransactionLog']['amount']; ?></td>
                                <td><?= $row['TransactionLog']['balance']; ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($row['TransactionLog']['date'])); ?></td>

                            </tr>
                        <?php } ?>
                    </table>
                <?php endif; ?>
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
