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

<h2>Commits</h2>

<div class="commit index">
<?php foreach ((array)$commits as $commit):?>

	<div class="commit row">
		<h3 class="name">
			<?php echo $chaw->commit($commit['Commit']['revision']);?>
		</h3>

		<span class="description">
			<?php echo $commit['Commit']['message'];?>
		</span>

		<span class="date">
			<?php echo $commit['Commit']['commit_date'];?>
		</span>

		<span class="author">
			<?php echo (!empty($commit['User']['username'])) ? $commit['User']['username'] : $commit['Commit']['author'];?>
		</span>

		<span class="admin">
			<?php echo $chaw->admin('delete', array('controller' => 'commits', 'action' => 'delete', $commit['Commit']['id']));?>
		</span>

	</div>

<?php endforeach;?>
</div>
<div class="paging">
<?php
	echo $paginator->prev();
	echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
	echo $paginator->next();
?>
</div>