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
			<?php echo $html->link($CurrentUser->username, array(
				'admin' => false, 'project' => false, 'fork' => false,
				'controller' => 'dashboard', 'action' => 'index'
				)); ?>
		</span>
		
		<span class="edit">
			<?php echo $html->link('edit', array(
				'admin' => false, 'project' => false, 'fork' => false,
				'controller' => 'users', 'action' => 'account'
			))?>
		</span>
		<span class="edit">
			<?php echo $html->link('logout', array(
				'admin' => false, 'project' => false, 'fork' => false,
				'controller' => 'users', 'action' => 'logout'
			))?>
		</span>
		
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
