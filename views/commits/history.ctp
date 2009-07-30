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
<h2>
	<?php __('Commits for') ?>

	<?php
		$title = null;
		if (!empty($CurrentProject->fork)) {
			$title = "forks / {$CurrentProject->fork} / ";
		}
		$title .= $CurrentProject->url;
		echo $html->link($title, array('controller' => 'source', 'action' => 'index'));
	?>
	<?php
		$path = '/';
		foreach ((array)$args as $part):
			$path .= $part . '/';
			echo '/' . $html->link(' ' . $part . ' ', array('controller' => 'source', 'action' => 'index', $path));
		endforeach;
		echo '/ ' . $html->link($current, array('controller' => 'source', 'action' => 'index', $path, $current));
	?>
</h2>

<div class="commits history">

	<?php $i = 0; foreach ((array)$commits as $commit): $zebra = ($i++ % 2) ? ' zebra' : null?>

		<div class="commit <?php echo $zebra?>">
			<strong>
				<?php echo $chaw->commit($commit['Repo']['revision'], (array)$CurrentProject);?>
			</strong>
			
			<div class="right">
				<p>
					<strong><?php __('Author') ?>:</strong> <?php echo $commit['Repo']['author'];?>
				</p>

				<p>
					<strong><?php __('Date') ?>:</strong> <?php echo $commit['Repo']['commit_date'];?>
				</p>
			</div>

			<p class="message">
				<?php echo $commit['Repo']['message'];?>
			</p>

			<div class="clear"><!----></div>

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