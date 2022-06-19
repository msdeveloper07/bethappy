<div class="container text-center">
    <div class="form-group">
        <p style="color:#a7ff00;"><i class="fa fa-check-circle fa-5x"></i></p>
        <?php if ($type == 'Deposit'): ?>
            <p><?= __('You have just deposited money, %s thanks you and wishes you good luck.', Configure::read('Settings.websiteName')); ?></p>
            <p><?= __('You can start playing.'); ?></p>
        <?php endif; ?>
        <?php if ($type == 'Withdraw'): ?>
            <p><?= __('Your withdrawal request has been successfully sent.'); ?></p>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-sm-6 offset-sm-3">
            <button class="btn btn-default rounded-pill px-4" onclick="window.top.location.href = '/';"><?= __('Go to games'); ?></button>
        </div>
    </div>  
</div>
<script>
    setTimeout(function () {
       self.parent.location.reload(true);
    }, 3000);
</script>