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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Game Activity'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Game Activity'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('reports_form'); ?><br/><br/>
            </div>
            <div class="col-md-12 pt-2">
                <?php if (!empty($data)): ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?= __('Game ID'); ?></th>
                                <th><?= __('Name'); ?></th>
                                <th><?= __('Image'); ?></th>
                                <th><?= __('Fun'); ?></th>
                                <th><?= __('Mobile'); ?></th>
                                <th><?= __('Date'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $game): ?>
                                <tr>
                                    <td><?= $game['IntGames']['id']; ?></td>
                                    <td><?= $game['IntGames']['name']; ?></td>
                                    <td class="showimage" id="<?= $game['IntGames']['id']; ?>">
                                        <img src="<?= $game['IntGames']['image']; ?>" style="width:100px;height:auto;"/>
                                    </td>
                                    <td><?= $game['IntGameActivity']['fun']; ?></td>
                                    <td><?= $game['IntGameActivity']['ismobile']; ?></td>
                                    <td><?= date("d-m-Y H:i:s", strtotime($game['IntGameActivity']['date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                
       
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
