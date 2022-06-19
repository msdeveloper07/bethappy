<style>
	.payment-logo {
		background: lightgrey;
		border-radius: 12px;
	}
</style>

<script>
	<?php
	if ($transaction['Vippass']['status'] == 11) {
	?>
		window.location.href = `${window.location.protocol}//${window.location.hostname}/payments/vippass/success`;
	<?php
	} else {
	?>	
		window.location.href = `${window.location.protocol}//${window.location.hostname}/payments/vippass/failed`;
	<?php
	}
	?>
</script>