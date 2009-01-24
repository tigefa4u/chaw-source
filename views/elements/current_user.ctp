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
			<?php if (empty($CurrentUser->active)): ?>
				<span class="activate link">
					<?php echo $html->link('activate', array(
						'admin' => false, 'project' => false, 'fork' => false,
						'controller' => 'users', 'action' => 'activate'
						), array('title' => 'activate your account')); ?>
				</span>
			<?php endif?>

			<span class="account link">
				<?php echo $html->link('account', array(
					'admin' => false, 'project' => false, 'fork' => false,
					'controller' => 'users', 'action' => 'account'
					), array('title' => 'edit your account')); ?>
			</span>
			<span class="dashboard link">
				<?php echo $html->link(__('dashboard',true), array(
					'admin' => false, 'project' => false, 'fork' => false,
					'controller' => 'dashboard', 'action' => 'index'
				), array('title' => 'view your dashboard'))?>
			</span>

			<span class="logout link">
				<?php echo $html->link(__('logout',true), array(
					'admin' => false, 'project' => false, 'fork' => false,
					'controller' => 'users', 'action' => 'logout'
				))?>
			</span>
		</div>
	<?php else:?>
		<span class="login">
			<?php echo $html->link(__('Login',true), array(
				'admin' => false, 'project' => false, 'fork' => false,
				'controller' => 'users', 'action' => 'login'
			)); ?>
			or
			<?php echo $html->link(__('Register',true), array(
				'admin' => false, 'project' => false, 'fork' => false,
				'controller' => 'users', 'action' => 'add'
			)); ?>
		</span>
	<?php endif;?>
</div>
