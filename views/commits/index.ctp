<?php
$script = '
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	$(".message").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>

<h2>Commits</h2>

<div class="commit index">
<?php foreach ((array)$commits as $commit):?>

	<div class="commit">

		<h3>
			<?php echo $chaw->commit($commit['Commit']['revision']);?>
		</h3>

		<p>
			<strong>Author:</strong> <?php echo $commit['Commit']['author'];?>
		</p>

		<p>
			<strong>Date:</strong> <?php echo $commit['Commit']['commit_date'];?>
		</p>

		<p class="message">
			<?php echo $commit['Commit']['message'];?>
		</p>

		<?php
			$changes = unserialize($commit['Commit']['changes']);
			if(!empty($changes)):
		?>
			<p>
				<strong>Changes:</strong>
				<ul>
				<?php
					foreach ($changes as $changed) :
						echo $html->tag('li', $changed);
					endforeach;

				?>
				</ul>
			</p>
		<?php endif?>

		<?php

			//echo (!empty($commit['Commit']['diff'])) ? $html->tag('pre', $html->tag('code', $commit['Commit']['diff'], array('class' => 'diff'))) : null;

		?>
	</div>

<?php endforeach;?>

<?php
	echo $paginator->prev();
	echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
	echo $paginator->next();
?>

</div>