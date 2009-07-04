<div id="admin-navigation">
	<h4><?php __('Admin') ?></h4>
	<ul>
		<li><?php
			$options = ($this->name == 'Dashboard') ? array('class' => 'on') : null;
			echo $html->link(__('Dashboard',true), array('admin' => true, 'controller' => 'dashboard', 'action' => 'index'), $options);
		?></li>
		<li><?php
			$options = ($this->name == 'Permissions') ? array('class' => 'on') : null;
			echo $html->link(__('Permissions',true), array('admin' => true, 'controller' => 'permissions', 'action' => 'index'), $options);
		?></li>
		<li><?php
			$options = ($this->name == 'Users') ? array('class' => 'on') : null;
			echo $html->link(__('Users',true), array('admin' => true, 'controller' => 'users', 'action' => 'index'), $options);
		?></li>
		<li><?php
			echo $html->link(__('Settings',true), array('admin' => false, 'controller' => 'projects', 'action' => 'edit'))
		?></li>
		<?php
			if ($CurrentProject->id == 1 && $this->params['isAdmin']) :
				$options = ($this->name == 'Projects') ? array('class' => 'on') : null;
				echo $html->tag('li', $html->link(__('Projects',true), array(
					'admin' => true, 'project'=> false, 'fork' => false,
					'controller' => 'projects', 'action' => 'index'), $options
				));
			endif;
		?>

	</ul>
	<p style="margin-top: 3em; margin-left: 10px;">
		<?php
			if ($CurrentProject->id == 1 && $this->params['isAdmin']) :
				echo $html->link(__('New Project',true), array(
					'admin' => true, 'project' => false, 'fork' => false,
					'controller' => 'projects', 'action' => 'add'
				));
			endif;
		?>
	</p>
</div>