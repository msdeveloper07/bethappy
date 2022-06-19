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
		<p style="color: #ff005c;"><i class="fa fa-exclamation-triangle fa-5x"></i></p>
		<h2><p><?= __('Transaction was cancelled by customer.'); ?></p></h2>
	</div>
</div>