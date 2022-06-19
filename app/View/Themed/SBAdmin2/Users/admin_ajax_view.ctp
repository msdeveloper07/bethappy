<?php $UserCategories = ClassRegistry::init('UserCategory'); ?>
<ul>
    <li class="litable"><b><?= __('Category'); ?>:</b>&nbsp;&nbsp;&nbsp;<?= $user_details['User']['category_id'];?>&nbsp;&nbsp;&nbsp;</li>
    <li class="litable"><b><?= __('First Name:'); ?></b>&nbsp;&nbsp;&nbsp;<?= $user_details['User']['first_name'];?>&nbsp;&nbsp;&nbsp;</li>
    <li class="litable"><b><?= __('Last Name:'); ?></b>&nbsp;&nbsp;&nbsp;<?= $user_details['User']['last_name'];?>&nbsp;&nbsp;&nbsp;</li>
    <li class="litable"><b><?= __('Phone:'); ?></b>&nbsp;&nbsp;&nbsp;<?= $user_details['User']['mobile_number'];?>&nbsp;&nbsp;&nbsp;</li>
    <li class="litable"><b><?= __('Date of Birth:'); ?></b>&nbsp;&nbsp;&nbsp;<?= $this->Beth->convertDate($user_details['User']['date_of_birth']);?>&nbsp;&nbsp;&nbsp;</li>
    <li class="litable"><b><?= __('Registration IP:'); ?></b>&nbsp;&nbsp;&nbsp;<?= $user_details['User']['ip'];?>&nbsp;&nbsp;&nbsp;</li>
    <li class="litable"><b><?= __('Last Visit:'); ?></b>&nbsp;&nbsp;&nbsp;<?= $this->Beth->convertDate($user_details['User']['last_visit']);?>&nbsp;&nbsp;&nbsp;</li>
    <li class="litable"><b><?= __('Login IP:'); ?></b>&nbsp;&nbsp;&nbsp;<?= $this->Html->link($user_details['User']['last_visit_ip'], array('controller' => 'users', 'action' => 'admin_user', $user_details['User']['last_visit_ip']), array('style' => 'color:#f89406'));?></li>
    <li class="litable"><b><?= __('Logout Time:'); ?></b>&nbsp;&nbsp;&nbsp;<?= $this->Beth->convertDate($user_details['User']['logout_time']);?>&nbsp;&nbsp;&nbsp;</li>
</ul>


