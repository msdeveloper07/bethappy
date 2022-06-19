<?php echo $this->Html->script("Games.gamewindow"); ?>
<?php echo $this->Html->script("Games.jquery"); ?>
<?php echo $this->Html->css('Games.login.form');?>
<div class="form-container">
	<div>
		<!-- Main Heading Starts -->
		<div class="text-center top-text">
			<h1><span>member</span> login</h1>
			<p>great to have you back!</p>
		</div>
		<!-- Main Heading Ends -->
			<!-- Form Starts -->
			<form class="custom-form" action="/games/venum/login" method="POST">
				<!-- Input Field Starts -->
				<div class="form-group">
					<label>Enter a player identifier:</label>
					<input class="form-control" type="text" name="loginname"  placeholder="loginname" required="">
					<input class="form-control" type="hidden" name="actionType" value="<?php echo isset( $_GET['action']) ? $_GET['action'] : ''; ?>">
				</div>
				
				<div class="form-group-btn">
					<button class="custom-button login btn-primary" type="submit"><span data-hover="login">login</span></button>
					<!-- <p class="text-center">don't have an account ? <a href="register.html">register now</a> -->
				</div>
			
			</form>
			<!-- Form Ends -->
	</div>
</div>

			