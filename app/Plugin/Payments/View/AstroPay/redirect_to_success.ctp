<style>
	.payment-logo {
		background: lightgrey;
		border-radius: 12px;
	}
</style>

<script>
	window.opener.location.href = `${window.location.protocol}//${window.location.hostname}/payments/astropay/success`;
	window.close();
</script>