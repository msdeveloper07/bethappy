<div id="users" class="login">
    <h3><?= __('Login'); ?></h3>

    <?php
        echo $this->Session->flash();
        echo $this->Session->flash('auth');
        echo $this->Form->create('User', array('action' => 'login'));
        echo $this->Form->input('username', array('label' => __('Username', true), 'class' => 'regi'));
        echo $this->Form->input('password', array('label' => __('Password', true), 'class' => 'regi'));
        ?>
        <div class="lefted"><?= $this->Form->submit(__('Login', true), array('class' => 'button')); ?></div>
        <?php
        echo $this->Form->end();
    ?>

    <div class="lefted">
        <?= $this->Html->link(__('Register now!', true), '#', array('class' => 'button', 'onclick' =>'registrationForm()')); ?>
        <?= $this->Html->link(__('Forgotten your password?', true), array('action' => 'reset'), array('class' => 'button')); ?>
    </div>
</div>