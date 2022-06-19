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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Player View'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Player View'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('user_tabs'); ?>
            </div>
            <div class="col-md-12 pt-2">
                <div class="row">

                    <div class="col-sm-12 col-md-5">
                        <div class="card">
                            <div class="card-header"><?= __('Player Information'); ?></div>
                            <div class="card-body">

                                <ul class="list-group mb-4">
                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Acount status'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?php
                                                switch ($fields['User']['status']) {
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
                                                ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Login status'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?php
                                                switch ($fields['User']['login_status']) {
                                                    case 'Logged Out':
                                                        echo '<button class="btn btn-sm btn-danger btn-table">' . __('Logged Out') . '</button>';
                                                        break;

                                                    case 'Logged In':
                                                        echo '<button class="btn btn-sm btn-success btn-table">' . __('Logged In') . '</button>';
                                                        break;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                                <hr>
                                <h3><?= __('Account Information'); ?></h3>
                                <ul class="list-group mt-4">

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('ID'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['id']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Username'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['username']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Email'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['email']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Balance'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['balance']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Currency'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['Currency']['name']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Phone number'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['mobile_number']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Language'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?php
                                                switch ($fields['Language']['ISO6391_code']) {
                                                    case 'en':
                                                        $flag = 'gb';
                                                        break;
                                                    case 'hi':
                                                        $flag = 'in';
                                                        break;
                                                    default:
                                                        $flag = $fields['Language']['ISO6391_code'];
                                                }
                                                ?>
                                                <img src="https://flagcdn.com/<?= strtolower($flag); ?>.svg" width="22"/>
                                                <?= $fields['Language']['name']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Bonus'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="bonusAllow" <?= $fields['User']['bonus_allow'] == 1 ? 'checked' : ''; ?>/>
                                                    <label class="custom-control-label" for="bonusAllow"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Newsletter'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="newsletter" <?= $fields['User']['newsletter'] == 1 ? 'checked' : ''; ?>/>
                                                    <label class="custom-control-label" for="newsletter"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>


                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Category'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['UserCategory']['name']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Registration date'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= date('d-m-Y H:i:s', strtotime($fields['User']['registration_date'])); ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Registration IP'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['ip']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Last visit'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= date('d-m-Y H:i:s', strtotime($fields['User']['last_visit'])); ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Last visit IP'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['last_visit_ip']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Logout time'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= date('d-m-Y H:i:s', strtotime($fields['User']['logout_time'])); ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Login Failure'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['login_failure']; ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <hr>
                                <h3><?= __('Personal Information'); ?></h3>
                                <ul class="list-group mt-4">

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('First name'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['first_name']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Last name'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['last_name']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Date of birth'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= date('d-m-Y', strtotime($fields['User']['date_of_birth'])); ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Gender'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['gender']; ?>
                                            </div>
                                        </div>
                                    </li>

                                </ul>

                                <hr>
                                <h3><?= __('Address Information'); ?></h3>
                                <ul class="list-group mt-4">

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Address'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['address1']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Zip code'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['zip_code']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('City'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $fields['User']['city']; ?>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= __('Country'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?php
                                                switch ($fields['Country']['alpha2_code']) {
                                                    case 'en':
                                                        $flag = 'gb';
                                                        break;
                                                    case 'hi':
                                                        $flag = 'in';
                                                        break;
                                                    default:
                                                        $flag = $fields['Country']['alpha2_code'];
                                                }
                                                ?>
                                                <img src="https://flagcdn.com/<?= strtolower($flag); ?>.svg" width="22"/>
                                                <?= $fields['Country']['name']; ?>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                                <hr>
                                <span id="more-less-wrapper" style="display:none;">
                                    <ul class="list-group">
                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Affiliate ID'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['affiliate_id']; ?>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Landing page'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['langing_page']; ?>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Group'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['Group']['name']; ?>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Odds type'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['odds_type']; ?>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Referal code'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['referal_id']; ?>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Confirmation code'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['confirmation_code']; ?>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Confirmation code created'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= date('d-m-Y H:i:s', strtotime($fields['User']['confirmation_code_created'])); ?>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Last visit session key'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['last_visit_sessionkey']; ?>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Last activity DB'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['last_activity_db']; ?>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Latitude'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['lat']; ?>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Longitude'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['lng']; ?>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Ezugi token'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['ezugitoken']; ?>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('Ezugi VIP level'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['ezugiviplevel']; ?>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item form-group small">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0">
                                                        <?= __('View type'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <?= $fields['User']['view_type']; ?>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </span>

                                <div class="text-center"><button class="btn btn-sm btn-success my-4" id="more-less"><?= __('Show/Hide'); ?></button></div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <?= __('Latest Notes'); ?>
                                    <!--<?//= $this->Html->link(__('View all', true), array('plugin' => false, 'controller' => 'users', 'action' => 'viewnotes', $fields['User']['id']), array('class' => 'btn btn-sm btn-info')); ?>-->
                                </div>
                            </div>
                            <div class="card-body" style="">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><?= __('Date'); ?></th>
                                            <th><?= __('Note'); ?></th>
                                            <th><?= __('Author'); ?></th>
                                            <!--<th><?//= __('Actions'); ?></th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($notes)): ?>
                                            <?php foreach ($notes as $row): ?>
                                                <tr>
                                                    <td><?= date("d-m-Y H:i", strtotime($row['Note']['created'])); ?></td>
                                                    <td><?= $row['Note']['content']; ?></td>
                                                    <td><?= $row['Author']['username']; ?></td>
        <!--                                                    <td>
                                                        <?//= $this->Html->link(__('Edit'), array('controller' => 'notes', 'action' => 'edit', $row['id']), array('class' => 'btn btn-sm btn-warning')); ?> 
                                                    </td>-->
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
                            <div class="card-footer text-muted">
                                <?= $this->element('dialog', array('user_id' => $fields['User']['id'])); ?>
                                <div class="float-right">
                                    <a data-toggle="modal" href="#UserNotedialog" class="btn btn-success btn-sm"><?= __('Add Note'); ?></a>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <?= __('Latest Alerts'); ?>
                                    <!--<?//= $this->Html->link(__('View all', true), array('plugin' => false, 'controller' => 'users', 'action' => 'viewalerts', $fields['User']['id']), array('class' => 'btn btn-sm btn-info')); ?>-->

                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-overflow">
                                    <thead>
                                        <tr>
                                            <th><?= __('Date'); ?></th>
                                            <th><?= __('Source'); ?></th>
                                            <th><?= __('Model'); ?></th>
                                            <th><?= __('Alert'); ?></th>
                                        </tr>
                                    </thead>

                                    <tbody> 
                                        <?php
                                        if (!empty($alerts)):

                                            foreach ($alerts as $row):
                                                ?>
                                                <tr>
                                                    <td><?= date('d-m-Y H:i', strtotime($row['Alert']['date'])); ?></td>
                                                    <td><?= $row['Alert']['alert_source']; ?></td>
                                                    <td><?= $row['Alert']['alert_model']; ?></td>
                                                    <td><?= $row['Alert']['alert_text']; ?></td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <tr>
                                                <td colspan="4"><?= __('No data to display.'); ?></td>
                                            </tr>

                                        <?php endif;
                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <br>
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <?= __('Latest Deposits'); ?>
                                    <!--<?//= $this->Html->link(__('View all', true), array('controller' => 'payments', 'action' => 'index', $fields['User']['id']), array('class' => 'btn btn-info btn-sm')); ?>-->

                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-overflow">
                                    <thead>
                                        <tr>
                                            <th><?= __('ID'); ?></th>
                                            <th><?= __('Date'); ?></th>
                                            <th><?= __('Method'); ?></th>
                                            <th><?= __('Amount'); ?></th>
                                            <th><?= __('Currency'); ?></th>
                                            <th><?= __('Status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($deposits)):
                                            foreach ($deposits as $row):
                                                ?>
                                                <tr>
                                                    <td><?= $row['Payment']['parent_id']; ?></td>
                                                    <td><?= date('d-m-Y H:i:s', strtotime($row['Payment']['created'])); ?></td>
                                                    <td><?= $row['Payment']['provider']; ?></td>
                                                    <td><?= $row['Payment']['amount']; ?></td>
                                                    <td><?= $row['Payment']['currency']; ?></td>
                                                    <td><?= $this->element('status_button', array('status' => $row['Payment']['status'])); ?></td>

                                                </tr>
                                                <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <tr>
                                                <td colspan="6"><?= __('No data to display.'); ?></td>
                                            </tr>

                                        <?php endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <br>
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <?= __('Latest Withdraws'); ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-overflow">
                                    <thead>
                                        <tr>
                                            <th><?= __('ID'); ?></th>
                                            <th><?= __('Date'); ?></th>
                                            <th><?= __('Method'); ?></th>
                                            <th><?= __('Amount'); ?></th>
                                            <th><?= __('Currency'); ?></th>
                                            <th><?= __('Status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($withdraws)):
                                            foreach ($withdraws as $row):
                                                ?>
                                                <tr>
                                                    <td><?= $row['Payment']['parent_id']; ?></td>
                                                    <td><?= date('d-m-Y H:i:s', strtotime($row['Payment']['created'])); ?></td>
                                                    <td><?= $row['Payment']['provider']; ?></td>
                                                    <td><?= $row['Payment']['amount']; ?></td>
                                                    <td><?= $row['Payment']['currency']; ?></td>
                                                    <td><?= $this->element('status_button', array('status' => $row['Payment']['status'])); ?></td>

                                                </tr>
                                                <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <tr>
                                                <td colspan="6"><?= __('No data to display.'); ?></td>
                                            </tr>

                                        <?php endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>

                        <!--                        <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <?//= __('Player Login Map'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="map-canvas" class="box-content" style="height:400px;"></div>
                                                    </div>
                                                </div>-->

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>





<style>
    #map-canvas img{ max-width: none; }

    .remove-box:hover {
        box-shadow: 0px 0px 5px #ccc;
    }

    .table-overflow tbody {
        display:block;
        height:400px;
        overflow:auto;
    }
    .table-overflow thead,   .table-overflow tbody tr {
        display:table;
        width:100%;
        table-layout:fixed;
    }
</style>



<script>
    $(document).ready(function () {
        //if (document.getElementById('map-canvas')) google.maps.event.addDomListener(window, 'load', initializemap);
        initializemap(<?= $fields['User']['id']; ?>);

        $('.close-btn').click(function () {
            var id = $(this).data('id'),
                    cookie_val = $.cookie('adminuserview');
            cookie_val = cookie_val.split(',');
            var pos = $.inArray(id.toString(), cookie_val);

            if (pos !== -1)
                cookie_val.splice(pos, 1);

            $.cookie('adminuserview', cookie_val.join(','), {path: '/'});
            $(this).parent().remove();
        });



    });

    $("#more-less").click(function () {
        console.log('toggle');
        $("#more-less-wrapper").toggle();
    });
</script>