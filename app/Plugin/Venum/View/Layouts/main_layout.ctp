<?php
	/**
	 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
	 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
	 *
	 * Licensed under The MIT License
	 * For full copyright and license information, please see the LICENSE.txt
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
	 * @link          https://cakephp.org CakePHP(tm) Project
	 * @package       app.View.Layouts
	 * @since         CakePHP(tm) v 0.10.0.1076
	 * @license       https://opensource.org/licenses/mit-license.php MIT License
	 */

	$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
	$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
	?>
<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php echo $cakeDescription ?>:
			<?php echo $this->fetch('title'); ?>
		</title>
		<?php
		
			echo $this->Html->meta('icon');
			echo $this->Html->css('cake.generic');
			echo $this->Html->css('Venum.games.index');
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	</head>
	<body>
		<div id="container">
			<div id="header">
				<div class="games-index-header">
					<div class="row">
						<div class="col-md-4"><h2>Venum Games</h2></div>
						<?php if ( isset ( $_SESSION['MyPlayerID'] ) ) { ?>	
							<div  class="col-md-2" id="loggedIn">
								Logged in as <?php echo "<b>".$_SESSION['MyPlayerID']."</b>"; ?>
							</div>
							<div  class="col-md-4 actionButtons">
								<div class="d-flex justify-content-end">
									<a href="/venum/games/deposit?action=deposit" class="btn btn-primary" id="spaceBetween">Deposit</a>  <a href="/venum/games/cashout?action=cashout" class="btn btn-primary" id="spaceBetween">Cashout</a>  <a href="/venum/games/customcashout?action=customcashout" class="btn btn-primary" id="spaceBetween">Custom Cashout</a> <a href="/venum/games/logout?action=logout" class="btn text-danger" id="spaceBetween"><i class="fa fa-sign-out-alt"></i>Log out</a><br/>
								</div>
							</div>
						<?php } else { ?>
							<div  class="col-md-8">
								<div class="d-flex justify-content-end">
									<a href="/venum/games/login?action=login" class="btn text-primary">
										<i class="fa fa-sign-in-alt"></i> Log in
									</a>
									<!-- <a href="/venum/VenumWallet/GetPlayerBalance" class="btn btn-primary">
										Get Player balance
									</a>
									<a href="/venum/VenumWallet/WithdrawAndDeposit" class="btn btn-primary">
									Withdraw And Deposit
									</a> -->
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				
			</div>
			<div id="content">

				<?php echo $this->Flash->render(); ?>
				<?php echo $this->fetch('content'); ?>

			
			</div>
			<div id="footer">
				<?php echo $this->Html->link($this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')), 'https://cakephp.org/', array('target' => '_blank', 'escape' => false, 'id' => 'cake-powered'));?>
				<p>
					<?php echo $cakeVersion; ?>
				</p>
			</div>
		</div>
		<!-- <?php echo $this->element('sql_dump'); ?> -->
	</body>
</html>
