<style> 
    /*    .user-options.nav-tabs > li > a,
        .dropdown-menu a{
            font-weight: bold;
            text-shadow: none;
        }
    
        .user-options .dropdown-menu {
            margin-top: -3px;
            background-color: transparent;
            border:none;
            box-shadow: none;
        }
    
        .user-options .dropdown-menu li {
            margin-bottom: 1px;
        }
        .inp {
            background: none repeat scroll 0 0 #003043;
            border: medium none;
            border-radius: 5px;
            color: #fff;
            font-size: 12px;
            font-weight: 300;
            margin: 0 5px;
            max-width: 418px;
            padding: 5px;
            position: relative;
            text-align: left;
        }*/
</style>

<ul class="nav nav-tabs user-options">
    <li class="nav-item dropdown">
        <?= $this->Html->link(_('Edit'), array('controller' => 'users', 'action' => 'edit', $fields['User']['id']), array('class' => 'nav-link')); ?>
    </li>
    <li class="nav-item dropdown">
        <?= $this->Html->link(_('Notes'), array('controller' => 'notes', 'action' => 'user_notes', $fields['User']['id']), array('class' => 'nav-link')); ?>
    </li>
    <li class="nav-item dropdown">
        <?= $this->Html->link(_('Alerts'), array('controller' => 'alerts', 'action' => 'user_alerts', $fields['User']['id']), array('class' => 'nav-link')); ?>
    </li>
<!--    <li class="nav-item dropdown">
        <?//= $this->Html->link(_('Bonuses'), array('controller' => 'bonuses', 'action' => 'view', $fields['User']['id']), array('class' => 'nav-link')); ?>
    </li>-->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><?= __('KYC'); ?></a>
        <ul class="dropdown-menu">
            <li><?= $this->Html->link(__('KYC Documents', true), array('plugin' => false, 'controller' => 'KYC', 'action' => 'userindex', $fields['User']['id']), array('class' => 'dropdown-item')); ?></li>
            <!--<li><?//= $this->Html->link(__('Change KYC Status', true), array('plugin' => false, 'controller' => 'users', 'action' => 'admin_kyc', $fields['User']['id']), array('class' => 'dropdown-item')); ?></li>-->
            <li><?= $this->Html->link(__('Request KYC Documents', true), array('plugin' => false, 'controller' => 'users', 'action' => 'admin_request_kycdoc', $fields['User']['id']), array('class' => 'dropdown-item')); ?></li>
            <li><?= $this->Html->link(__('Upload KYC Documents', true), array('plugin' => false, 'controller' => 'KYC', 'action' => 'admin_add_userkyc', $fields['User']['id']), array('class' => 'dropdown-item')); ?></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><?= __('Logs'); ?><span class="caret" style="margin-left: 10px;"></span></a>
        <ul class="dropdown-menu">
            <li><?= $this->Html->link(__('Casino Log', true), array('plugin' => false, 'controller' => 'TransactionLog', 'action' => 'viewlog', $fields['User']['id']), array('class' => 'dropdown-item',)); ?></li>
            <li><?= $this->Html->link(__('Payments Log', true), array('plugin' => 'payments', 'controller' => 'Reports', 'action' => 'user_payments', $fields['User']['id']), array('class' => 'dropdown-item',)); ?></li>
            <li><?= $this->Html->link(__('Login/Logout History', true), array('plugin' => false, 'controller' => 'UserLogs', 'action' => 'viewlog', $fields['User']['id']), array('class' => 'dropdown-item')); ?></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><?= __('User Settings'); ?><span class="caret" style="margin-left: 10px;"></span></a>
        <ul class="dropdown-menu">
            <li><?= $this->Html->link(__('User Risk Settings', true), array('plugin' => false, 'controller' => 'UsersSettings', 'action' => 'risk', $fields['User']['id']), array('class' => 'dropdown-item',)); ?></li>
            <li><?= $this->Html->link(__('User Deposit Risk Settings', true), array('plugin' => false, 'controller' => 'UsersSettings', 'action' => 'depositrisk', $fields['User']['id']), array('class' => 'dropdown-item',)); ?></li>
            <li><?= $this->Html->link(__('Custom User Settings', true), array('plugin' => false, 'controller' => 'UsersSettings', 'action' => 'index', $fields['User']['id']), array('class' => 'dropdown-item')); ?></li>
        </ul>
    </li>

    <!--<?//php if (isset($fields['User']['isaffiliate_id'])): ?>
        <li class="nav-item"><?//= $this->Html->link(__('Affiliate Settings', true), array('plugin' => false, 'controller' => 'Affiliates', 'action' => 'editbyid', $fields['User']['isaffiliate_id']), array('class' => 'nav-link')); ?></li>
    <?//php else: ?>
        <li class="nav-item"><?//= $this->Html->link(__('Affiliate Settings', true), array('plugin' => false, 'controller' => 'Affiliates', 'action' => 'addaffiliate', $fields['User']['id']), array('class' => 'nav-link')); ?></li>
    <?//php endif; ?>-->
    <li class="nav-item"><?= $this->Html->link(__('Game Activity', true), array('plugin' => 'int_games', 'controller' => 'int_games', 'action' => 'getgameactivity', $fields['User']['id']), array('class' => 'nav-link')); ?></li>
<!--    <li><?//= $this->Html->link(__('Bonus', true), array('plugin' => false, 'controller' => 'bonus', 'action' => 'index', $fields['User']['id']),  array('class' => 'btn btn-mini btn-success')); ?></li>
    <li><?//= $this->Html->link(__('Add Bonus', true), array('plugin' => false, 'controller' => 'bonus', 'action' => 'add', $fields['User']['id']),  array('class' => 'btn btn-mini btn-success')); ?></li>-->
    <li class="nav-item"><?= $this->Html->link(__('Limits', true), array('plugin' => false, 'controller' => 'users', 'action' => 'lgalimits', $fields['User']['id']), array('class' => 'nav-link')); ?></li>
    <!--<li><?//= $this->Html->link(__('Player liability Report', true), array('plugin' => false, 'controller' => 'reports', 'action' => 'playerliabilityreport', $fields['User']['id']), array('class' => 'btn btn-mini btn-primary')); ?></li>-->
    <li class="nav-item"><?= $this->Html->link(__('Kick', true), array('plugin' => false, 'controller' => 'users', 'action' => 'kick', $fields['User']['id']), array('class' => 'nav-link bg-danger')); ?></li>

</ul>

<div class="modal hide fade" id="myModal">
    <div class="modal-body">
        <div class="mid-cent">
            <h3><?= __('Add KYC Documents to user:'); ?> <b><?= $fields['User']['username']; ?></b> </h3>
            <div class="cent-txt txt-pad">
                <div class="span12">
                    <?= __('Please upload files into the following form'); ?>
                </div>
                <div>
                    <i><span class="error-message"><?= $this->Session->flash(); ?></span></i>
                </div>
                <?= $this->Form->create('Post', array('type' => 'file')); ?>
                <table>
                    <tr>
                        <th><?= __('Identity Card (back view)'); ?></th>
                        <td><?= $this->Form->input('file1', array('label' => '', 'type' => 'file', 'class' => 'inp')); ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Identity Card (front view)'); ?></th>
                        <td><?= $this->Form->input('file2', array('label' => '', 'type' => 'file', 'class' => 'inp')); ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Bank Account'); ?></th>
                        <td><?= $this->Form->input('file3', array('label' => '', 'type' => 'file', 'class' => 'inp')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?= $this->Form->submit(__('Confirm changes', true), array('class' => 'btn btn-success', 'div' => false)); ?>
        <a href="#" class="btn" data-dismiss="modal"><?= __('Close'); ?></a>
        <?= $this->Form->end(); ?>
    </div>
</div>