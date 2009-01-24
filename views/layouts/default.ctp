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
		<?php echo $CurrentProject->name .'/' . $title_for_layout;?>
	</title>
	<?php
		echo $html->meta('icon');
		if (isset($rssFeed)) {
			echo $html->meta('rss', $html->url($rssFeed, true));
		}
		echo $html->css(array('generic', 'chaw'));

		if (!empty($javascript)) {
			echo $javascript->link('jquery-1.2.6.min');
			echo $javascript->link('gshowdown.min');

			$base = $this->webroot;
			if (!empty($this->params['fork'])) {
				$base .= 'forks/' . $this->params['fork'] . '/';
			}
			$base .= $this->params['project'] . '/';

			echo $javascript->codeBlock('
				var converter = new Showdown.converter("' . str_replace('//', '/', $base) . '");

				$(document).ready(function(){
					$(".wiki-text").each(function () {
						$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
					});
				});
			');
		}
		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">

		<div id="header">

			<h1><?php echo $html->link($CurrentProject->name, array(
					'admin' => false,
					'controller' => 'source', 'action' => 'index'
				));
			?></h1>

			<div id="navigation">
				<ul>
					<li><?php
						$options = ($this->name == 'Source') ? array('class' => 'on') : null;
						echo $html->link('Source', array(
							'admin' => false,
							'controller' => 'source', 'action' => 'index'), $options);
					?></li>

					<li><?php
						$options = ($this->name == 'Timeline') ? array('class' => 'on') : null;
						echo $html->link(__('Timeline',true), array(
							'admin' => false,
							'controller' => 'timeline', 'action' => 'index'), $options);
					?></li>

					<li><?php
						$options = ($this->name == 'Wiki') ? array('class' => 'on') : null;
						echo $html->link(__('Wiki',true), array(
							'admin' => false,
							'controller' => 'wiki', 'action' => 'index'), $options);
					?></li>

					<li><?php
						$options = ($this->name == 'Tickets') ? array('class' => 'on') : null;
						echo $html->link(__('Tickets',true), array(
							'admin' => false,
							'controller' => 'tickets', 'action' => 'index'), $options);
					?></li>

					<li><?php
						$options = ($this->name == 'Versions') ? array('class' => 'on') : null;
						echo $html->link(__('Versions',true), array(
							'admin' => false,
							'controller' => 'versions', 'action' => 'index'), $options);
					?></li>

					<li><?php
						$options = ($this->name == 'Projects') ? array('class' => 'on') : null;
						echo $html->link(__('Projects',true), array(
							'admin' => false, 'project'=> false, 'fork' => false,
							'controller' => 'projects', 'action' => 'index'), $options);
					?></li>

					<?php if (!empty($this->params['isAdmin'])):?>

						<li><?php
							$options = (!empty($this->params['admin'])) ? array('class' => 'on') : null;
							echo $html->link(__('Admin',true), array(
								'admin' => true,
								'controller' => 'dashboard', 'action' => 'index'), $options);
						?></li>

					<?php endif;?>

				</ul>
			</div>

		</div>

		<div id="content">
			<?php
				echo $this->element('current_user');

				$session->flash();
			?>
			<div id="page-content">
				<?php
					echo $content_for_layout;
				?>
				<div class="clear"><!----></div>
			</div>

		</div>

		<div id="footer">
			<p>
				<span>
					<?php echo $html->link(__('About',true), '/pages/about');?>
				</span>
				<?php echo $html->link(
						$html->image('cake.power.gif', array('alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")),
						'http://www.cakephp.org/',
						array('target'=>'_new'), null, false
					);
				?>
			</p>
		</div>
	</div>
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var pageTracker = _gat._getTracker("UA-743287-5");
		pageTracker._trackPageview();	} catch(err) {}
</script>
</body>
</html>