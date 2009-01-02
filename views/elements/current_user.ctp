<div id="current-user">
	<?php if (!empty($CurrentUser)):?>

		<span class="gravatar">
			<?php
				$gravatar = "http://www.gravatar.com/avatar/" . md5($CurrentUser->email). "?"
				 	. "size=22";
				echo "<img src=\"{$gravatar}\" />";
			?>
		</span>

		<span class="username">
			<?php echo $html->link($CurrentUser->username.'', array(
				'admin' => false, 'project' => false, 'fork' => false,
				'controller' => 'dashboard', 'action' => 'index'
				), array('title' => 'view your dashboard')); ?>
		</span>

		<div class="links">
			<span class="account link">
				<?php echo $html->link('account', array(
					'admin' => false, 'project' => false, 'fork' => false,
					'controller' => 'users', 'action' => 'account'
					), array('title' => 'edit your account')); ?>
			</span>
			<span class="dashboard link">
				<?php echo $html->link('dashboard', array(
					'admin' => false, 'project' => false, 'fork' => false,
					'controller' => 'dashboard', 'action' => 'index'
				), array('title' => 'view your dashboard'))?>
			</span>

			<span class="logout link">
				<?php echo $html->link('logout', array(
					'admin' => false, 'project' => false, 'fork' => false,
					'controller' => 'users', 'action' => 'logout'
				))?>
			</span>
		</div>
	<?php else:?>
		<span class="login">
			<?php echo $html->link('Login', array(
				'admin' => false, 'project' => false, 'fork' => false,
				'controller' => 'users', 'action' => 'login'
			)); ?>
			or
			<?php echo $html->link('Register', array(
				'admin' => false, 'project' => false, 'fork' => false,
				'controller' => 'users', 'action' => 'add'
			)); ?>
		</span>
	<?php endif;?>
</div>
