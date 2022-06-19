<div class="container text-center">
    <div class="form-group">
        <p style="color:#a7ff00;"><i class="fa fa-check-circle fa-5x"></i></p>
        <?php if ($type == 'deposit'): ?>
            <p><?= __('You have just deposited money, %s thanks you and wishes you good luck.', Configure::read('Settings.defaultTitle')); ?></p>
            <p><?= __('You can start playing.'); ?></p>
        <?php endif; ?>
        <?php if ($type == 'withdraw'): ?>
            <p><?= __('Your withdrawal request has been successfully sent.'); ?></p>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-sm-4 offset-sm-4">
            <button class="btn-modal" onclick="window.top.location.href = '/';"><?= __('Go to games'); ?></button>
        </div>
    </div>  
</div>