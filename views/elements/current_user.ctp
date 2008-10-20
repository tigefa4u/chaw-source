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
			<?php echo $html->link($CurrentUser->username, array('controller' => 'users', 'action' => 'account', $CurrentUser->username)); ?>
		</span>
	<?php else:?>
		<span class="login">
			<?php echo $html->link('Login', array('controller' => 'users', 'action' => 'login')); ?>
		</span>
		<span class="register">
			<?php echo $html->link('Register', array('controller' => 'users', 'action' => 'add')); ?>
		</span>
	<?php endif;?>
</div>
