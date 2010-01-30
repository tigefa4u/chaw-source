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
 * @license			commercial
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset();?>
	<title>
		<?php
			echo env('HTTP_HOST') . '/' .  $CurrentProject->name . '/' . $title_for_layout;
		?>
	</title>
	<?php
		echo $html->meta('icon');
		if (isset($rssFeed)) {
			echo $html->meta('rss', $html->url($rssFeed, true));
		}
		echo $html->css(array('generic', 'chaw'));

		if (!empty($this->params['admin'])) {
			echo $html->css(array('chaw.admin'));
		}
		echo $html->script('jquery-1.3.1.min');

		if (isset($showdown)):
			echo $html->script('gshowdown.min');
			echo $html->scriptBlock('
				var converter = new Showdown.converter("' . $chaw->base() . '");
				$(document).ready(function(){
					$(".wiki-text").each(function () {
						$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
					});
				});
			');
		endif;
		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">

		<div id="header">

			<h1>
				<?php
					$options = ($this->name == 'Projects') ? array('class' => 'project-link on') : array('class' => 'project-link');
					echo $html->link(__('Projects',true), array(
						'admin' => false, 'plugin' => null, 'project'=> false, 'fork' => false,
						'controller' => 'projects', 'action' => 'index'
					), $options);

				 	if ($this->name != 'Projects') {
						echo ' / ' . $html->link($CurrentProject->name, array(
							'admin' => false,
							'controller' => 'source', 'action' => 'index'
						));
					}
				?>
			</h1>

			<?php echo $this->element('current_user');?>

				<div id="navigation">
					<?php if ($this->name !== 'Projects'):?>
					
					<ul>
						<li><?php
							$options = ($this->name == 'Source') ? array('class' => 'on') : null;
							echo $html->link(__('Source',true), array(
								'admin' => false, 'plugin' => null,
								'controller' => 'source', 'action' => 'index'), $options);
						?></li>

						<li><?php
							$options = ($this->name == 'Timeline') ? array('class' => 'on') : null;
							echo $html->link(__('Timeline',true), array(
								'admin' => false, 'plugin' => null,
								'controller' => 'timeline', 'action' => 'index'), $options);
						?></li>

						<li><?php
							$options = ($this->name == 'Wiki') ? array('class' => 'on') : null;
							echo $html->link(__('Wiki',true), array(
								'admin' => false, 'plugin' => null,
								'controller' => 'wiki', 'action' => 'index'), $options);
						?></li>

						<li><?php
							$options = ($this->name == 'Tickets') ? array('class' => 'on') : null;
							echo $html->link(__('Tickets',true), array(
								'admin' => false, 'plugin' => null,
								'controller' => 'tickets', 'action' => 'index'), $options);
						?></li>

						<li><?php
							$options = ($this->name == 'Versions') ? array('class' => 'on') : null;
							echo $html->link(__('Versions',true), array(
								'admin' => false, 'plugin' => null,
								'controller' => 'versions', 'action' => 'index'), $options);
						?></li>

						<?php if (!empty($this->params['isAdmin'])):?>

							<li><?php
								$options = (!empty($this->params['admin'])) ? array('class' => 'on') : null;
								echo $html->link(__('Admin',true), array(
									'admin' => true, 'plugin' => null,
									'controller' => 'dashboard', 'action' => 'index'), $options);
							?></li>

						<?php endif;?>

					</ul>
				<?php endif;?>
				</div>
		</div>
		<div id="content">
			<?php
				$this->Session->flash();
			?>
			<?php
				if (!empty($this->params['admin'])):
					echo $this->element('admin_navigation');
					echo $html->tag('div', $content_for_layout, array('id' => 'admin-content'));
				else:
					echo $html->tag('div', $content_for_layout, array('id' => 'page-content'));
				endif;
			?>
		</div>

		<div id="footer">
			<p>
				<span>
					<?php echo $html->link(__('About',true), '/pages/about');?>
				</span>
				<?php echo $html->link(
						$html->image('cake.power.gif', array('alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")),
						'http://www.cakephp.org/',
						array('target'=>'_new', 'escape' => false)
					);
				?>
			</p>
		</div>
	</div>
<?php if (Configure::read() == 0):?>
	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		try {
			var pageTracker = _gat._getTracker("UA-11048416-3");
			pageTracker._trackPageview();	} catch(err) {}
	</script>
<?php endif;?>
</body>
</html>