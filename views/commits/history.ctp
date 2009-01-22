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
		<?php __('History') ?> /
		<?php
			$title = null;
			if (!empty($CurrentProject->fork)) {
				$title = "forks / {$CurrentProject->fork} / ";
			}
			$title .= $CurrentProject->url;
			echo $html->link($title, array('controller' => 'browser', 'action' => 'index'));
		?>
		<?php
			$path = '/';
			foreach ((array)$args as $part):
				$path .= $part . '/';
				echo '/' . $html->link(' ' . $part . ' ', array($path));
			endforeach;
			echo '/ ' . $html->link($current, array('controller' => 'browser', 'action' => 'index', $path, $current));
		?>
	</h2>
	<?php foreach ((array)$commits as $commit):?>

		<div class="commit">

			<h4>
				<?php echo $chaw->commit($commit['Repo']['revision']);?>
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

			<?php
				if(!empty($commit['Repo']['changes'])):
			?>
				<div class="changes">
					<strong><?php __('Changes') ?>:</strong>
					<ul>
					<?php
						foreach ($commit['Repo']['changes'] as $changed) :
							echo $html->tag('li', $changed);
						endforeach;
					?>
					</ul>
				</div>
			<?php endif?>

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