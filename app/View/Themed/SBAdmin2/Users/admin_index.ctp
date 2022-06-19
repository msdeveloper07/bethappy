<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Players'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Players'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Players'); ?></h1>
                <a href="/admin/users/add" class="btn btn-success px-4"><?= __('Create Player');?></a>
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
                <p>You can use the *(asterisk) symbol as a wildcard when performing a search.</p>
                <p>For example:  searching for sam* would tell the database to look for all possible endings to that root.</p>  
                <p>Results will include Samuel, Sampson, Sampras etc.</p>
                <div class="table-responsive pt-2">
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
                                    <th style="text-align: center"><?= $this->Paginator->sort('ID'); ?></th>
                                    <th><?= $this->Paginator->sort('Username'); ?></th>
                                    <th><?= $this->Paginator->sort('first_name'); ?></th>
                                    <th><?= $this->Paginator->sort('last_name'); ?></th>
                                    <th><?= $this->Paginator->sort('e-Mail'); ?></th>
                                    <th><?= $this->Paginator->sort('mobile_number', __('Mobile number')); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('country'); ?></th>
                                    <!--<th style="text-align: center"><?//= $this->Paginator->sort('Affiliate ID'); ?></th>-->
                                    <th style="text-align: center"><?= $this->Paginator->sort('status', __('Account Status')); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('login_status', __('Login Status')); ?></th>

                                    <th style="text-align: center"><?= $this->Paginator->sort('kyc', __('KYC')); ?></th>
                                    <th style="text-align: right"><?= $this->Paginator->sort('currency'); ?></th>
                                    <th style="text-align: right"><?= $this->Paginator->sort('balance'); ?></th>
                                    <th><?= $this->Paginator->sort('registration_date'); ?></th>
                                    <th><?= $this->Paginator->sort('registration_ip', 'Registration IP'); ?></th>
                                    <th><?= $this->Paginator->sort('last_visit_ip', 'Last Visit IP'); ?></th>
                                    <th style="text-align: center"><?= $this->Paginator->sort('OVL'); ?></th>
                                    <th style="text-align: center;"><?= __('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($data as $field):
                                    //var_dump($field); 
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $field['User']['id']; ?></td>
                                        <td>
                                            <?php
                                            if ($field['User']['category_id'] != '') {
                                                echo $this->Html->link($field['User']['username'], array('action' => 'view', $field['User']['id']), array('style' => 'font-weight:bold; color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $field['User']['id'], 'data-popbox' => 'pop1'));
                                            } else {
                                                echo $this->Html->link($field['User']['username'], array('action' => 'view', $field['User']['id']), array('style' => 'color:' . $field['User']['category_id'], 'class' => 'popper', 'data-id' => $field['User']['id'], 'data-popbox' => 'pop1'));
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align: center;"><?= $field['User']['first_name']; ?></td>
                                        <td style="text-align: center;"><?= $field['User']['last_name']; ?></td>
                                        <td><?= $field['User']['email']; ?></td>
                                        <td><?= $field['User']['mobile_number']; ?></td>
                                        <td style="text-align: center;">
                                            <!--<img src="https://www.countryflags.io/<?//= $field['User']['Country']['alpha2_code']; ?>/shiny/24.png"> <?= $field['User']['Country']['name']; ?>-->
                                            <img src="https://flagcdn.com/<?= strtolower($field['User']['Country']['alpha2_code']); ?>.svg" width="30"/> <?= $field['User']['Country']['name']; ?>
                                        </td>
                                        <!--<td style="text-align: center;"><a href="/admin/affiliates/viewbyid/<?//= $field['User']['affiliate_id']; ?>"><?//= $field['User']['affiliate']['Affiliate']["affiliate_custom_id"]; ?></a></td>-->
                                        <td style="text-align: center;">
                                            <?php
                                            switch ($field['User']['status']) {
                                                case 'Unconfirmed':
                                                    echo '<button class="btn btn-sm btn-warning btn-table">' . __('Unconfirmed') . '</button>';
                                                    break;

                                                case 'Active':
                                                    echo '<button class="btn btn-sm btn-success btn-table">' . __('Active') . '</button>';
                                                    break;
                                                case 'Locked Out':
                                                    echo '<button class="btn btn-sm btn-danger btn-table">' . __('Locked Out') . '</button>';
                                                    break;
                                                case 'Self Excluded':
                                                    echo '<button class="btn btn-sm btn-danger btn-table">' . __('Self Excluded') . '</button>';
                                                    break;
                                                case 'Self Deleted':
                                                    echo '<button class="btn btn-sm btn-danger btn-table">' . __('Self Deleted') . '</button>';
                                                    break;
                                            }
                                            ?></td>
                                        <td style="text-align: center;">
                                            <?php
                                            switch ($field['User']['login_status']) {
                                                case 'Logged Out':
                                                    echo '<button class="btn btn-sm btn-danger btn-table">' . __('Logged Out') . '</button>';
                                                    break;

                                                case 'Logged In':
                                                    echo '<button class="btn btn-sm btn-success btn-table">' . __('Logged In') . '</button>';
                                                    break;
                                            }
                                            ?></td>

                                        <td style="text-align: center;">
                                            <?php
                                            if ($field['User']['kyc']) {
                                                $kyc = array();

                                                foreach ($field['User']['kyc'] as $kyc_data) {

                                                    if ($kyc_data['KYC']['kyc_type'] == 1) {
                                                        ?>
                                                        <div class="d-flex">ID: &nbsp; <?= $this->element('status_button', array('status' => $kyc_data['KYC']['status'], 'text' => true)); ?></div>
                                                    <?php } elseif ($kyc_data['KYC']['kyc_type'] == 2) { ?>

                                                        <div class="d-flex">ADDRESS:&nbsp; <?= $this->element('status_button', array('status' => $kyc_data['KYC']['status'], 'text' => true)); ?></div>
                                                    <?php } elseif ($kyc_data['KYC']['kyc_type'] == 3) {
                                                        ?>

                                                        <div class="d-flex">FUNDING:&nbsp;  <?= $this->element('status_button', array('status' => $kyc_data['KYC']['status'], 'text' => true)); ?></div>
                                                        <?php
                                                    }
                                                };

                                                //echo $this->Html->link($kyc, array('controller' => 'users', 'action' => 'kyc', $field['User']['id']), array('style' => 'font-size:11px;font-weight:700;color:' . $color));
                                            } else {
                                                echo __('No documents uploaded yet.');
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align: right;"><?= $field['User']['Currency']['name'] ?></td>
                                        <td style="text-align: right;"><?= $field['User']['Currency']['code'] . $field['User']['balance']; ?></td>
                                        <!--$this->Beth->convertDate($field['User']['registration_date']);-->
                                        <td><?= date("d-m-Y H:i:s", strtotime($field['User']['registration_date'])); ?></td>
                                        <td><?= $this->Html->link($field['User']['ip'], array('controller' => 'users', 'action' => 'admin_user', $field['User']['ip'])); ?></td>
                                        <td><?= $this->Html->link($field['User']['last_visit_ip'], array('controller' => 'users', 'action' => 'admin_user', $field['User']['last_visit_ip'])); ?></td>
                                        <td style="text-align: center;">
                                            <?php
                                            //var_dump($field['User']['OVL']);
                                            switch ($field['User']['OVL']) {
                                                case 0:
                                                    echo "No settings yet.";
                                                    break;

                                                case 1:
                                                    echo '<a class="btn btn-sm btn-primary" href="/admin/UsersSettings/risk/' . $field['User']['id'] . '">Settings</a>';
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td style="min-width: 190px;text-align: center;" class="actions">
                                            <?php
                                            //var_dump($actions);
                                            foreach ($actions as $action) {
                                                if ($action['action'] == 'delete' || $action['action'] == 'cancel' || $action['action'] == 'complete') {
                                                    $delete = __('Are you sure?');
                                                } else {
                                                    $delete = NULL;
                                                }

                                                if (isset($action['controller'])) {
                                                    echo $this->Html->link($action['name'], array('plugin' => isset($action['plugin']) ? $action['plugin'] : NULL, 'controller' => $action['controller'], 'action' => $action['action'], $field['User']['id']), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);
                                                } else {
                                                    echo $this->Html->link($action['name'], array('plugin' => isset($action['plugin']) ? $action['plugin'] : NULL, 'controller' => $this->params['controller'], 'action' => $action['action'], $field['User']['id']), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);
                                                }

                                                echo ' ';
                                            }
                                            ?>
                                        </td>
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
</div>

<div class="small-table popbox" id="pop1" data-popbox="pop1"></div>
