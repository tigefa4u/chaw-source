<?php
/* SVN FILE: $Id: default.ctp 6296 2008-01-01 22:18:17Z phpnut $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.console.libs.templates.skel.views.layouts
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 6296 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-01 14:18:17 -0800 (Tue, 01 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>
		<?php echo Configure::read('Project.name') .' : ' . $title_for_layout;?>
	</title>
	<?php
		echo $html->charset();
		echo $html->meta('icon');

		echo $html->css(array('generic', 'chaw', 'chaw.admin'));

		echo $javascript->link('jquery-1.2.6.min');

		echo $javascript->link('gshowdown');

		//echo $javascript->link('smartarea');

		//echo $javascript->link('MeatballSocietyCreoleV0.4');

		//echo $javascript->link(array('wiky', 'wiky.lang', 'wiky.math'));

		echo $scripts_for_layout;

	?>
</head>
<body class="admin">
	<div id="container">
		<div id="header">

			<span class="admin">
				<?php echo $admin->link('edit', array('controller' => 'projects', 'action' => 'edit'))?>
			</span>

			<h1><?php echo $html->link(Configure::read('Project.name'), array('admin' => false,'controller' => 'wiki', 'action' => 'index'));?></h1>

			<div id="navigation">
				<ul>
					<li><?php
						$options = ($this->name == 'Wiki') ? array('class' => 'on') : null;
						echo $html->link('Wiki', array('admin' => false, 'controller' => 'wiki', 'action' => 'index'), $options);
					?></li>

					<li><?php
						$options = ($this->name == 'Timeline') ? array('class' => 'on') : null;
						echo $html->link('Timeline', array('admin' => false, 'controller' => 'timeline', 'action' => 'index'), $options);
					?></li>

					<li><?php
						$options = ($this->name == 'Tickets') ? array('class' => 'on') : null;
						echo $html->link('Tickets', array('admin' => false, 'controller' => 'tickets', 'action' => 'index'), $options);
					?></li>

					<li><?php
						$options = ($this->name == 'Browser') ? array('class' => 'on') : null;
						echo $html->link('Source', array('admin' => false, 'controller' => 'browser', 'action' => 'index'), $options);
					?></li>

					<li><?php
						$options = ($this->name == 'Versions') ? array('class' => 'on') : null;
						echo $html->link('Versions', array('admin' => false, 'controller' => 'versions', 'action' => 'index'), $options);
					?></li>
				</ul>
			</div>

		</div>
		<div id="content">


			<?php
				echo $this->element('current_user');

				$session->flash();
			?>

			<div class="clear"><!----></div>

			<div id="admin-navigation">
				<h4>Admin</h4>
				<ul>
					<li><?php
						$options = ($this->name == 'Projects') ? array('class' => 'on') : null;
						echo $html->link('Projects', array('admin' => true, 'controller' => 'projects', 'action' => 'index'), $options);
					?></li>
					<li><?php
						$options = ($this->name == 'Users') ? array('class' => 'on') : null;
						echo $html->link('Users', array('admin' => true, 'controller' => 'users', 'action' => 'index'), $options);
					?></li>
					<li><?php
						$options = ($this->name == 'Permissions') ? array('class' => 'on') : null;
						echo $html->link('Permissions', array('admin' => true, 'controller' => 'permissions', 'action' => 'index'), $options);
					?></li>

				</ul>
				<p style="margin-top: 3em; margin-left: 10px;">
					<?php
						echo $html->link('New Project', array('admin' => false, 'controller' => 'projects', 'action' => 'add'));
					?>
				</p>
			</div>

			<div id="admin-content">

				<?php
					echo $content_for_layout;
				?>
			</div>


			<div class="clear"><!----></div>

		</div>


		<div id="footer">
			<?php echo $html->link(
					$html->image('cake.power.gif', array('alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")),
					'http://www.cakephp.org/',
					array('target'=>'_new'), null, false
				);
			?>
		</div>
	</div>
	<?php echo $cakeDebug?>
</body>
</html>