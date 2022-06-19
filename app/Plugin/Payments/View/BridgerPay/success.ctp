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
<script type='text/javascript'>
    function iframeURLChange(iframe, callback) {
        var unloadHandler = function () {
            // Timeout needed because the URL changes immediately after
            // the `unload` event is dispatched.
            setTimeout(function () {
                callback(iframe.contentWindow.location.href);
            }, 0);
        };

        function attachUnload() {
            // Remove the unloadHandler in case it was already attached.
            // Otherwise, the change will be dispatched twice.
            iframe.contentWindow.removeEventListener("unload", unloadHandler);
            iframe.contentWindow.addEventListener("unload", unloadHandler);
        }

        iframe.addEventListener("load", attachUnload);
        attachUnload();
    }

    iframeURLChange(document.getElementById("bridgerpay-iframe"), function (newURL) {
        console.log("URL changed:", newURL);
    });

    if (top !== self)
        top.location.href = self.location.href;
</script>