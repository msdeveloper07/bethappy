
<?php echo $this->Html->script("Games.gamewindow"); ?>
<?php echo $this->Html->script("Games.customfunction"); ?>
<?php echo $this->Html->script("Games.jquery"); ?>
<?php echo $this->Html->css('Games.login.form');?>

<div class="form-container">
	<div>
		<!-- Main Heading Starts -->
		<div class="text-center top-text">
			<h1><span>Get Player</span> Balance</h1>
			<p>Check player balance!</p>
		</div>
		<!-- Main Heading Ends -->
			<!-- Form Starts -->
			<form class="custom-form" action="/games/VenumWallet/GetPlayerBalance" method="POST">
				<!-- Input Field Starts -->
				<div class="form-group">
					<input class="form-control" type="hidden" name="operatorId" value="atlanticslotEURtest">
					<?php if(isset($_SESSION['MyPlayerID'])) {  ?>
						<input class="form-control" type="text" name="UserId"  placeholder="UserId" required="" value="<?php echo $_SESSION['MyPlayerID'] ;?>">
					<?php }else{?>
						<input class="form-control" type="text" name="UserId"  placeholder="UserId" required="">
					<?php }?>
					<input class="form-control" type="text" name="GameId"  placeholder="GameId" required="" onkeyup="return forceLower(this);">
					<input class="form-control" type="text" name="GameBrand"  placeholder="GameBrand" required="" onkeyup="return forceLower(this);">
					<input class="form-control" type="text" name="SessionId"  placeholder="SessionId">
					<input class="form-control" type="text" name="Reason"  placeholder="Reason" required="">
				</div>
				<div class="form-group-btn">
					<button class="custom-button login btn-primary" type="submit"><span data-hover="login">Check</span></button>
					<!-- <p class="text-center">don't have an account ? <a href="register.html">register now</a> -->
				</div>
			</form>
			<!-- Form Ends -->
	</div>
</div>

			