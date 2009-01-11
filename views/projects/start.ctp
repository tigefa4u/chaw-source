<style>
	.start {
		width: auto;
	}

	.start .public, .start .private {
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
</style>
<div class="start">
	<h2><?php __('Start A Project')?></h2>
	<?php
		//__('');

	?>

	<div class="public">
		<?php echo $html->image('ohloh.png', array('width' => 52));?>

		<div class="description">
			<h3><?php __('Public')?></h3>
			<p>
				Public projects are the coolest
			</p>
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

			<p>Private projects are cool</p>
			<p><?php echo $html->link('Start a private project', array('private'))?></p>

		</div>

	</div>

	<div class="clear"><!----></div>

</div>