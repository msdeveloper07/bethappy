<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Jackpots'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Atlantic Megapot'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Atlantic Megapot'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive-sm pt-2">
                    <?= __('The data will show players who have:'); ?>
                    <ul>
                        <li><?= __('single deposit >= 250'); ?></li>
                        <li><?= __('cumulative deposits >= 6000'); ?></li>
                        <li><?= __('ordered by cumulative deposits and GGR in descending order'); ?></li>
                    </ul>

                    <?= __('Please choose your timeframe, otherwise the report will show data for the current month.'); ?><br/><br/>
                    <?= $this->element('reports_form'); ?><br/><br/>


                    <div class="table-responsive">
                        <?php if (!empty($data)): ?>

                            <table class="table table-bordered" style="font-size:12px;">
                                <thead>
                                    <tr>
                                        <th><?= __('Player'); ?></th>
                                        <th><?= __('Max deposit'); ?></th>
                                        <th><?= __('Cumulative deposits'); ?></th>
                                        <th><?= __('GGR'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $record): ?>                                     
                                        <tr>
                                            <td><?= $record['User']['username']; ?></td>
                                            <td><?= $record['Currency']['currency_code'] . $record[0]['max_deposit']; ?></td>
                                            <td><?= $record['Currency']['currency_code'] . $record[0]['cumulative_deposits']; ?></td>
                                            <td><?= $record['Currency']['currency_code'] . $record[0]['GGR']; ?></td>
                                        </tr>

                                    <?php endforeach;
                                    ?> 
                                </tbody>
                            </table>
                        <?php else: ?>
                            <?= __('No data to display.'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
