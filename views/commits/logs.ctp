<?php
$script = '
$(document).ready(function(){
	$(".message").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>

<div class="commits history">
	<h2>
		<?php __('Logs') ?>
	</h2>
	<?php foreach ((array)$commits as $commit):?>

		<div class="commit">

			<h4>
				<?php echo $chaw->commit($commit['Repo']['revision'], (array)$CurrentProject);?>
			</h4>

			<p>
				<strong><?php __('Author') ?>:</strong> <?php echo $commit['Repo']['author'];?>
			</p>

			<p>
				<strong><?php __('Date') ?>:</strong> <?php echo $commit['Repo']['commit_date'];?>
			</p>

			<p class="message">
				<?php echo $commit['Repo']['message'];?>
			</p>

		</div>

	<?php endforeach;?>

</div>
<div class="paging">
<?php
	$paginator->options(array('url' => $this->passedArgs));
	echo $paginator->prev();
	echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
	echo $paginator->next();
?>
</div>