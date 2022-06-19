<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Payments'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Limits'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Limits'); ?></h1>
                <a href="/admin/limits/add" class="btn btn-success px-4"><?= __('Create Limit'); ?></a>
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
            
               <?= $this->element('list'); ?>
            
            
            <div class="col-md-12">
                <div class="table-responsive-sm pt-2">
                   <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="text-align: center"><?= $this->Paginator->sort('ID'); ?></th>
                                    <th><?= $this->Paginator->sort('limit type'); ?></th>
                                    <th><?= $this->Paginator->sort('country'); ?></th>
                                    <th><?= $this->Paginator->sort('currency'); ?></th>
                                    <th><?= $this->Paginator->sort('provider'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('user'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('min'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('max'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('daily'); ?></th>
                                    <th><?= $this->Paginator->sort('monthly'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('created'); ?></th>  
                                    <th style="text-align: center"><?= $this->Paginator->sort('modified'); ?></th>                             
                                    <th style="text-align: center;"><?= __('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $field):?>
                                    <tr>
                                        <td style="text-align: center;"><?= $field['Limit']['id']; ?></td>
                                        <td style="text-align: center;"><?= $field['Limit']['limit_type']; ?></td>
                                        <td style="text-align: left;"> <img src="https://www.countryflags.io/<?= $field['Country']['alpha2_code']; ?>/shiny/24.png"><?= $field['Country']['name']; ?></td>
                                        <td style="text-align: center;"><?= $field['Currency']['name']; ?></td>
                                        <td style="text-align: center;"><?= $field['Limit']['provider_id']; ?></td>
                                        <td style="text-align: center;"><?= $field['User']['username']; ?></td>
                                        <td style="text-align: center;"><?= $field['Limit']['min']; ?></td>
                                        <td style="text-align: center;"><?= $field['Limit']['max']; ?></td>
                                        <td style="text-align: center;"><?= $field['Limit']['daily']; ?></td>
                                        <td style="text-align: center;"><?= $field['Limit']['monthly']; ?></td>
                                        <td style="text-align: center;"><?= $field['Limit']['created']; ?></td>
                                        <td style="text-align: center;"><?= $field['Limit']['modified']; ?></td>
                                        <td style="min-width: 190px;text-align: center;" class="actions">                                        
                                            <?php
             
                                            foreach ($actions as $action) {
                                                if ($action['action'] == 'delete' || $action['action'] == 'cancel' || $action['action'] == 'complete') {
                                                    $delete = __('Are you sure?');
                                                } else {
                                                    $delete = NULL;
                                                }
                                                if (isset($action['controller'])) {
                                                    echo $this->Html->link($action['name'], array('plugin' => isset($action['plugin']) ? $action['plugin'] : NULL, 'controller' => $action['controller'], 'action' => $action['action'], $field['Limit']['id']), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);
                                                } else {
                                                    echo $this->Html->link($action['name'], array('plugin' => isset($action['plugin']) ? $action['plugin'] : NULL, 'controller' => $this->params['controller'], 'action' => $action['action'], $field['Limit']['id']), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);
                                                }

                                                echo ' ';
                                            }
                                            ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

