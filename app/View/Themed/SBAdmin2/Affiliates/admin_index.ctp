<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Affiliates'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Affiliates List'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Affiliates List'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('search'); ?>

                <?php // echo $this->element('tabs'); ?>
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
                                    <th><?= $this->Paginator->sort('id'); ?></th>
                                    <th><?= $this->Paginator->sort('affiliate_custom_id'); ?></th>
                                    <th><?= $this->Paginator->sort('parent_id'); ?></th>
                                    <th><?= $this->Paginator->sort('user_id'); ?></th>                                            
                                    <th><?= $this->Paginator->sort('created'); ?></th>
                                    <th><?= $this->Paginator->sort('modified'); ?></th>
                                    <th><?= $this->Paginator->sort('percentage'); ?></th>
                                    <th><?= $this->Paginator->sort('lc_percentage'); ?></th>
                                    <th><?= $this->Paginator->sort('monthly_gain'); ?></th>  
                                    <?php if (isset($actions) AND is_array($actions) AND ! empty($actions)): ?>
                                        <th><?= __('Actions'); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php
                                $i = 1;
                                foreach ($data as $field) {
                                    $class = null;
                                    if ($i++ % 2 == 0)
                                        $class = ' alt';
                                    ?>
                                    <tr>
                                        <td><?= $field['Affiliate']['id']; ?></td>
                                        <td><?= $this->Html->link($field['Affiliate']['affiliate_custom_id'], array('controller' => 'affiliates', 'action' => 'view', $field['Affiliate']['id'])); ?></td>
                                        <td><?= (!empty($field['Affiliate']['parent_id']) ? $this->Html->link($field['Affiliate']['parent_id'], array('controller' => 'affiliates', 'action' => 'view', $field['Affiliate']['parent_id'])) : __('none')); ?></td>
                                        <td><?= $this->Html->link($field['User']['username'], array('controller' => 'users', 'action' => 'view', $field['User']['id'])); ?></td>
                                        <td><?= date('d-m-Y H:i:s', strtotime($field['Affiliate']['created'])); ?></td>
                                        <td><?= date('d-m-Y H:i:s', strtotime($field['Affiliate']['modified'])); ?></td>
                                        <td><?= $field['Affiliate']['percentage']; ?></td>
                                        <td><?= $field['Affiliate']['lc_percentage']; ?></td>
                                        <td><?= $field['Affiliate']['monthly_gain']; ?></td>
                                        <?php if (isset($actions) AND is_array($actions) AND ! empty($actions)) { ?>
                                            <td style="min-width:190px;" class="actions <?= $class; ?>">
                                                <?php
                                                foreach ($actions as $action) {
                                                    echo $this->MyHtml->link($action['name'], array('controller' => 'affiliates', 'action' => $action['action'], $field['Affiliate']['id']), array('class' => isset($action['class']) ? $action['class'] : ''));
                                                }
                                                ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?= $this->element('paginator'); ?>
                    <?php else: ?>
                        <p><?= __('No records found.'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>




<!--<style> 
    .color-palette {
        font-size: 13pt;
        display: block;
        line-height: 16px;
        padding: 0px 5px;
        font-family: Arial,sans-serif;
        color: #FFF !important;
        text-shadow: 0px 1px rgba(0, 0, 0, 0.25);
        border-style: solid;
        border-radius: 10px;
        box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.08), 0px 1px rgba(255, 255, 255, 0.3) inset;
    }
</style>

<div class="hidden-name" id="user"></div>-->
