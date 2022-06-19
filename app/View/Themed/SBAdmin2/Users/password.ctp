<div id="users" class="password">
    <h3><?php echo __('Change password'); ?></h3>

    <?php
        echo $this->Session->flash();
        if (!isset($success)) {
            echo $this->Form->create('User', array('action' => 'password', 'inputDefaults' => array('type' => 'password')));
            echo $this->Form->input('password', array('label' => __('Old Password', true), 'class' => 'regi'));
            echo $this->Form->input('new_password', array('label' => __('New Password', true), 'class' => 'regi'));
            echo $this->Form->input('new_password_confirm', array('label' => __('Confirm password', true), 'class' => 'regi'));
            ?>
            <div class="centered">
                <?php echo $this->MyHtml->spanLink(__('Change Password', true), '#', array('class' => 'button-blue', 'onClick' => "jQuery('#UserPasswordForm').submit()")); ?>
            </div>
            <?php echo $this->Form->end();
        }
    ?>
</div>