<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Bonuses'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Bonuses'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Bonuses List'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">

            <div class="col-md-12">
                <?= $this->element('search'); ?><br/>
                <?= $this->element('tabs'); ?>
            </div>

            <div class="col-md-12">
                <div class="table-responsive pt-2">
                    <?php if (!empty($data)): ?>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <?php
                                    $model = array_keys($data[0]);
                                    $model = $model[0];
                                    $titles = $data[0][$model];
                                    foreach ($titles as $title => $value) {
                                        if ($title == 'type_id' || $title == 'user_id' || $key == 'bonus_type_id')
                                            continue;
                                        if (($title != 'locale')) {
                                            ?>
                                            <th><?= $this->Paginator->sort(__($title)); ?></th>
                                            <?php
                                        }
                                    }
                                    ?>              
                                    <?php if (isset($actions) AND is_array($actions) AND ! empty($actions)) { ?>
                                        <th style="text-align: center;"><?= __('Actions'); ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($data as $field) {
                                    $class = null;
                                    if ($i++ % 2 == 0)
                                        $class = ' alt';

                                    echo "<tr>";
                                    $k = 0;
                                    foreach ($field[$model] as $key => $var) {

                                        //var_dump($var);
                                        //TODO better locale field handling
                                        if ($key == 'type_id' || $key == 'user_id' || $key == 'bonus_type_id')
                                            continue;
                                        if ($key != 'locale') {
                                            $t = $this->Text->truncate(strip_tags($var), 100, array('ending' => '...', 'exact' => false));
                                            if (isset($mainField) && $k == $mainField) {
                                                $t = $this->Html->link($t, array('action' => 'view', $field[$model]['id']));
                                            }
                                            echo "<td class=\"{$class}\">";
                                            if ($key == 'amount' || $key == 'payoff_amount' || $key == 'penalty_amount') {
                                                //var_dump($field['Bonus']['User']);
                                                $t = $field['Bonus']['User']['Currency']['code'] . $t;
                                                //$t = $t . ' ' . Configure::read('Settings.currency');
                                            } else if ($key == 'percentage') {
                                                $t = $t . ' %';
                                            }
                                            if ($key == 'created' || $key == 'activated' || $key == 'released') {
                                                //$t = $this->Beth->convertDate($field['Bonus']['created']);                                               
                                                $t = date("d-m-Y H:i:s", strtotime($field['Bonus'][$key]));
                                            }

                                            if ($key == 'User') {
                                                //var_dump($var);
                                                $t = $this->Html->link($var['User']['username'], array('controller' => 'users', 'action' => 'view', $var['User']['id']));
                                            }
                                            if ($key == 'name') {
                                                //var_dump($var);
                                                $t = $this->Html->link($var, array('controller' => 'BonusTypes', 'action' => 'edit', $var['Bonus']['type_id']));
                                            }


                                            if ($key == 'status') {

                                                $t = $this->element('status_button', array('status' => $var));
                                            }

//                                            else if ($key == 'user_id') {
//                                               
//                                                $t = $this->Html->link($t, array('controller' => 'users', 'action' => 'view', $t));
//                                            }



                                            echo $t . "</td>";
                                        }
                                        $k++;
                                    }
                                    if (isset($actions) AND is_array($actions) AND ! empty($actions)) {
                                        echo "<td style='min-width: 190px;text-align: center' class=\"actions {$class}\">\n";
                                        foreach ($actions as $action) {
                                            if ($action['action'] == 'delete' || $action['action'] == 'deactivate' || $action['action'] == 'activate') {
                                                $delete = _('Are you sure?');
                                            } else {
                                                $delete = NULL;
                                            }
                                            if (isset($action['controller'])) {
                                                echo $this->Html->link($action['name'], array('controller' => $action['controller'], 'action' => $action['action'], $field[$model]['id']), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);
                                            } else {
                                                echo $this->Html->link($action['name'], array('controller' => $this->params['controller'], 'action' => $action['action'], $field[$model]['id']), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);
                                            }
                                            echo ' ';
                                        }
                                        echo "</td>";
                                    }
                                    ?>
                                <?php } ?>
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
</div>


