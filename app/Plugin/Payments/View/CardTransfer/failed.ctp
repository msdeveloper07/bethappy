<div class="container text-center">
    <div class="form-group">
        <p style="color: #ff005c;"><i class="fa fa-exclamation-triangle fa-5x"></i></p>
        <?php if ($type == 'Deposit'): ?>
            <p><?= __('Your deposit failed.'); ?></p>
        <?php endif; ?>
        <?php if ($type == 'Withdraw'): ?>
            <p><?= __('Your withdraw request failed.'); ?></p>
        <?php endif; ?>
        <p><?= __('Please try again or contact support.'); ?></p>
    </div>
    <div class="row">
        <div class="col-sm-4 offset-sm-4">
            <a class="btn btn-default rounded-pill px-4" href="mailto:<?= Configure::read('Settings.websiteEmail'); ?>"><?= __('Contact Support'); ?> at <?= Configure::read('Settings.websiteEmail'); ?></a>
        </div>
    </div>
</div>
<script>
    setTimeout(function () {
        self.parent.location.reload(true);
    }, 3000);
</script>