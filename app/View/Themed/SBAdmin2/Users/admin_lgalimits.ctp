
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
                        <?= $this->Html->link(__('Player View'), ['plugin' => false, 'controller' => 'users', 'action' => 'view', 'prefix' => 'admin', $fields['User']['id']], ['escape' => false, 'title' => __('Player View')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Player Limits'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Player Limits'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('tabs'); ?>
            </div>
            <div class="col-md-12 pt-2">
                <!--<?//= $this->Form->create($model, array('type' => 'file')); ?>-->

                <div class="card">
                    <div class="card-header"><?= __('Deposit Limits'); ?></div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= __('Limit Type'); ?></th>
                                    <th><?= __('Limit Amount'); ?></th>
                                    <th><?= __('Created'); ?></th>
                                    <th><?= __('Expires'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($limits['deposit']['data'])): ?>
                                    <?php foreach ($limits['deposit']['data'] as $key => $limit): ?>
                                        <tr>
                                            <td><?= $limit['UsersLimits']['limit_type']; ?></td>
                                            <td><?= $limit['UsersLimits']['amount']; ?></td>
                                            <td><?= $limit['UsersLimits']['apply_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['apply_date'])) : ''; ?></td>
                                            <td><?= $limit['UsersLimits']['until_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['until_date'])) : ''; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4"><?= __('No data to display.'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header"><?= __('Wager Limits'); ?></div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= __('Limit Type'); ?></th>
                                    <th><?= __('Limit Amount'); ?></th>
                                    <th><?= __('Created'); ?></th>
                                    <th><?= __('Expires'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($limits['wager']['data'])): ?>
                                    <?php foreach ($limits['wager']['data'] as $key => $limit): ?>
                                        <tr>
                                            <td><?= $limit['UsersLimits']['limit_type']; ?></td>
                                            <td><?= $limit['UsersLimits']['amount']; ?></td>
                                            <td><?= $limit['UsersLimits']['apply_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['apply_date'])) : ''; ?></td>
                                            <td><?= $limit['UsersLimits']['until_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['until_date'])) : ''; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4"><?= __('No data to display.'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header"><?= __('Loss Limits'); ?></div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= __('Limit Type'); ?></th>
                                    <th><?= __('Limit Amount'); ?></th>
                                    <th><?= __('Created'); ?></th>
                                    <th><?= __('Expires'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($limits['loss']['data'])): ?>
                                    <?php foreach ($limits['loss']['data'] as $key => $limit): ?>
                                        <tr>
                                            <td><?= $limit['UsersLimits']['limit_type']; ?></td>
                                            <td><?= $limit['UsersLimits']['amount']; ?></td>
                                            <td><?= $limit['UsersLimits']['apply_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['apply_date'])) : ''; ?></td>
                                            <td><?= $limit['UsersLimits']['until_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['until_date'])) : ''; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4"><?= __('No data to display.'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header"><?= __('Self-exclision Limits'); ?></div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= __('Limit Type'); ?></th>
                                    <th><?= __('Limit Amount'); ?></th>
                                    <th><?= __('Created'); ?></th>
                                    <th><?= __('Expires'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($limits['selfexclusion']['data'])): ?>
                                    <?php foreach ($limits['selfexclusion']['data'] as $key => $limit): ?>

                                        <tr>
                                            <td><?= $limit['UsersLimits']['limit_type']; ?></td>
                                            <td><?= $limit['UsersLimits']['amount']; ?></td>
                                            <td><?= $limit['UsersLimits']['apply_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['apply_date'])) : ''; ?></td>
                                            <td><?= $limit['UsersLimits']['until_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['until_date'])) : ''; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4"><?= __('No data to display.'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header"><?= __('Delete Account'); ?></div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= __('Limit Type'); ?></th>

                                    <th><?= __('Created'); ?></th>
                                    <th><?= __('Expires'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($limits['deleteaccount']['data'])): ?>
                                    <?php foreach ($limits['deleteaccount']['data'] as $key => $limit): ?>

                                        <tr>
                                            <td><?= $limit['UsersLimits']['limit_type']; ?></td>
                                            <td><?= $limit['UsersLimits']['apply_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['apply_date'])) : ''; ?></td>
                                            <td><?= $limit['UsersLimits']['until_date'] != NULL ? date('d-m-Y H:i', strtotime($limit['UsersLimits']['until_date'])) : ''; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3"><?= __('No data to display.'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>




