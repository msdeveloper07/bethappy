<div class="container text-center">
    <div class="form-group">
        <p style="color: #ff005c;"><i class="fa fa-exclamation-triangle fa-5x"></i></p>
        <?php if ($type == 'deposit'): ?>
            <p><?= __('Your deposit failed.'); ?></p>
        <?php endif; ?>
        <?php if ($type == 'withdraw'): ?>
            <p><?= __('Your withdraw request failed.'); ?></p>
        <?php endif; ?>
        <p><?= __('Please try again or contact support.'); ?></p>
    </div>
    <div class="row">
        <div class="col-sm-4 offset-sm-4">
            <button class="btn-modal" onclick="parent.framecancel()"><?= __('Contact Support'); ?></button>
        </div>
    </div>
</div>