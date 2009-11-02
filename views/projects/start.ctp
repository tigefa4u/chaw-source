<?php if (empty($CurrentUser->active)) :?>
	<h2><?php __('You must activate your account')?></h2>
	<p>
		<?php echo $html->link('activate now', array(
			'admin' => false, 'project' => false, 'fork' => false,
			'controller' => 'users', 'action' => 'activate'
			), array('title' => 'activate your account')); ?>
	</p>
<?php return; endif;?>
<style>
	.start {
		width: auto;
	}

	.start .public, .start .private {
		clear: both;
		margin: 40px 0;
	}

	.start div img {
	 	float: left;
		margin-right: 20px;
		veritcal-align: middle;
	}

	.start div .description {
		float: left;
		veritcal-align: middle;
	}
	.start div h3 {
		margin: 2px 0 6px 0;
		padding: 0;
	}
	.start a {
		text-decoration: underline;
	}
</style>
<div class="start">
	<h2><?php __('Start A Project')?></h2>

	<div class="public">
		<?php echo $html->image('ohloh.png', array('width' => 52));?>

		<div class="description">
			<h3><?php __('Public')?></h3>
			<p>
				Going Open Source? A public project will give you a great opportunity to connect
				with other developers.
			</p>
			<p>
				By registering your project on <a href="http://www.ohloh.net">Ohloh</a>
				we can grab information about your project from the
				<a href="http://www.ohloh.net/api/getting_started">Ohloh API</a>.
			<p>
				<a href="https://www.ohloh.net/p/new">
					<?php __('Register your project on Ohloh')?>
				</a>, then
				<?php echo $html->link('Start a public project', array('public'))?>
			</p>
		</div>

	</div>

	<div class="clear"><!----></div>

	<div class="private">
		<?php echo $html->image('/css/images/lock.gif', array('width' => 50));?>

		<div class="description">
			<h3><?php __('Private')?></h3>

			<p>
				Client Work? Keep your project private and only allow people you know to
				access your project.
			</p>

			<p><?php echo $html->link('Start a private project', array('private'))?></p>

		</div>

	</div>

	<div class="clear"><!----></div>

</div>