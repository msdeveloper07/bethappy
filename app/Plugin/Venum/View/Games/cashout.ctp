<?php echo $this->Html->script("Venum.gamewindow"); ?>
<?php echo $this->Html->script("Venum.jquery"); ?>
<?php echo $this->Html->css('Venum.login.form');?>
<div class="form-container">
	<div>
		<!-- Main Heading Starts -->
		<div class="text-center top-text">
			<h1><span>Cash out from your</span> Wallet</h1>
			<p>For play as Live!</p>
		</div>
		<!-- Main Heading Ends -->
			<!-- Form Starts -->
			<form class="custom-form" action="/venum/games/cashout" method="POST">
				<!-- Input Field Starts -->
				<div class="form-group">
				<label>Enter amount to cash out:</label>
					<input class="form-control" type="text" name="cashoutamount"  placeholder="cashoutamount" required="">
					<input class="form-control" type="hidden" name="actionType" value="<?php echo isset( $_GET['action']) ? $_GET['action'] : ''; ?>">
				</div>
				
				<div class="form-group-btn">
					<button class="custom-button login btn-primary" type="submit"><span data-hover="Cash Out">Cash Out</span></button>
					<!-- <p class="text-center">don't have an account ? <a href="register.html">register now</a> -->
				</div>
			
			</form>
			<!-- Form Ends -->
	</div>
</div>

			