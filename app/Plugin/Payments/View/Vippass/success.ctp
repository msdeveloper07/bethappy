<style>
	body {
		background: #19648e;
	}
</style>

<div class="container">
	<div class="text-center mb-3 mt-4">
		<img src="<?=$method['PaymentMethod']['image']?>" height="100" />
	</div>

	<div class="text-center mt-5" id="deposit-failed">
        <p style="color:#a7ff00;"><i class="fa fa-check-circle fa-5x"></i></p>
        <h2>
            <p><?= __('You have just deposited money, %s thanks you and wishes you good luck.', Configure::read('Settings.websiteName')); ?></p>
            <p><?= __('You can start playing.'); ?></p>
        </h2>
        <div class="text-center mt-4">
            <button class="btn btn-default rounded-pill px-4" onclick="goGames();"><?= __('Go to games'); ?></button>
        </div>
	</div>
</div>

<script>
    function goGames() {
        window.opener.top.location.href = '/';
	    window.close();
    }
</script>