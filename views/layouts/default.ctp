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
<!doctype html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
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
		echo $html->css(array(
			'http://li3.rad-dev.org/css/base.css',
			'http://li3.rad-dev.org/css/li3.css',
			'li3.chaw'
		));
		//echo $html->css(array('generic', 'chaw'));

		if (!empty($this->params['admin'])) {
			echo $html->css(array('chaw.admin'));
		}

		if (!empty($javascript)) {
			echo $html->script('http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js');
			echo $html->script('http://li3.rad-dev.org/js/li3.js');
			echo $html->script('http://li3.rad-dev.org/js/cli.js');
			echo $html->script('http://li3.rad-dev.org/js/libraries/ZeroClipboard/ZeroClipboard.js');

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
		}
		echo $scripts_for_layout;
	?>
</head>
<body class="chaw">
<div id="wrapper">
	<div id="cli">
		<div id="cli-display"></div>
		<div>
			<form id="cli-form" onSubmit="return false">
				<input type="text" id="cli-input" />
				<input id="cli-submit" type="submit" />
			</form>
		</div>
	</div>
	<div id="git-shortcuts">
		<span id="git-clone-path" class="clone fixed"><strong>git clone</strong> code@rad-dev.org:lithium.git</span>
		<a href="#" id="git-copy" class="copy" title="Copy the git clone shortcut to your clipboard">copy to clipboard</a>
	</div>
	<div id="account-navigation">
		<div id="account-navigation-toggler">
			<a href="/users/account" title="manage your account">account</a>
		</div>
		<div class="contents" style="display:none;">
			<?php echo $this->element('current_user');?>
		</div>
	</div>
	<div id="container">
		<div id="header">
			<h1 class="project-link"><?php
				$options = ($this->name == 'Projects') ? array('class' => 'on') : array();
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
			?></h1>
			<div class="project-details">
			<?php
				if ($this->name !== 'Projects') {
					echo $this->element('project_details');
				}
			?>
			</div>
			<?php
				$session->flash();
			?>
		</div>
		<div id="left-bar">
		<?php if ($this->name !== 'Projects'):?>
			<ul class="chaw-navigation">
				<li class="source<?php echo ($this->name == 'Source') ? ' active' : null; ?>"><?php

					echo $html->link(__('Source',true), array(
						'admin' => false, 'plugin' => null,
						'controller' => 'source', 'action' => 'index'));
				?></li>

				<li class="timeline<?php echo ($this->name == 'Timeline') ? ' active' : null; ?>"><?php
					echo $html->link(__('Timeline',true), array(
						'admin' => false, 'plugin' => null,
						'controller' => 'timeline', 'action' => 'index'));
				?></li>

				<li class="wiki<?php echo ($this->name == 'Wiki') ? ' active' : null; ?>"><?php
					echo $html->link(__('Wiki',true), array(
						'admin' => false, 'plugin' => null,
						'controller' => 'wiki', 'action' => 'index'));
				?>
				</li>
				<?php if ($this->name == 'Wiki'): ?>
					<li class="wiki-nav">
						<?php echo $this->element('wiki_navigation', compact('subNav', 'wikiNav', 'recentEntries')); ?>
					</li>
				<?php endif ?>
				<li class="tickets<?php echo ($this->name == 'Tickets') ? ' active' : null; ?>"><?php
					echo $html->link(__('Tickets',true), array(
						'admin' => false, 'plugin' => null,
						'controller' => 'tickets', 'action' => 'index'));
				?></li>
				<li class="versions<?php echo ($this->name == 'Versions') ? ' active' : null; ?>"><?php
					echo $html->link(__('Versions',true), array(
						'admin' => false, 'plugin' => null,
						'controller' => 'versions', 'action' => 'index'));
				?></li>
				<?php
				/*

				<li class="about"><?php echo $html->link(__('About',true), '/pages/about');?></li>

				*/ ?>
				<?php if (!empty($this->params['isAdmin'])):?>

					<li class="admin <?php echo (!empty($this->params['admin'])) ? 'active' : null; ?>"><?php
						echo $html->link(__('Admin',true), array(
							'admin' => true, 'plugin' => null,
							'controller' => 'dashboard', 'action' => 'index'));
					?></li>

				<?php endif;?>
			</ul>
		<?php endif;?>

		<?php if (!empty($this->params['admin'])):
			echo $this->element('admin_navigation');
		endif; ?>
		</div>
		<div id="content" class="<?php echo empty($this->params['admin']) ? '' : 'admin'; ?>">
			<?php echo $content_for_layout; ?>
		</div>
	</div>
	<div id="footer-spacer"></div>
</div>
<div id="footer">
	<p class="copyright">Pretty much everything is &copy; 2009 and beyond, the Union of Rad</p>
	<p>
		<?php echo $html->link(
			$html->image('cake.power.gif', array(
					'alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")
			),
			'http://www.cakephp.org/',
				array('target'=>'_new', 'escape' => false)
			);
		?>
	</p>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function () {
		li3.setup({
			base : null,
			testimonials: false
		});
		li3Cli.setup();
	});
</script>
<?php if (Configure::read() == 0):?>
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var pageTracker = _gat._getTracker("UA-11048416-1");
		pageTracker._trackPageview();	} catch(err) {}
</script>
<?php endif;?>
</body>
</html>