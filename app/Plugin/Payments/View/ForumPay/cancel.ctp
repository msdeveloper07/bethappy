<style>
	body {
		background: #19648e;
	}
</style>

<div class="container vh-100 d-flex align-items-center justify-content-center">
	<div>
		<div class="text-center mb-5">
			<img src="<?=$method['PaymentMethod']['image']?>" height="100" />
		</div>

		<div class="text-center mt-5" id="deposit-failed">
			<p class="text-warning"><i class="fa fa-exclamation-triangle fa-5x"></i></p>
			<h2><p><?= __('Transaction was cancelled.'); ?></p></h2>
			<h2><p><?=__("Reason")?>: <?= $result["reason"]?></p></h2>
		</div>
	</div>
</div>