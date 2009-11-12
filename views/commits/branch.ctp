<?php
$script = '
$(document).ready(function(){
	$(".message").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$html->scriptBlock($script, array('inline' => false));
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
<?php /*
<h3>Branches: <?php
		$branchesLinks = array();
		foreach((array)$branches as $branch) :
			$branchesLinks[] = $html->link($branch, $chaw->url((array)$CurrentProject, array(
				'controller' => 'commits', 'action' => 'branch', $branch
			)));
		endforeach;
		echo implode(' | ', $branchesLinks);
	?></h3>
*/ ?>

<div class="commits history">
<table>
	<tbody>
		<th>Commit</th>
		<th>Message</th>
		<th>Author</th>
		<th>Date</th>
	</tbody>
	<tbody>
		<?php $i = 0; foreach ((array)$commits as $commit): $zebra = ($i++ % 2) ? ' zebra' : null?>

			<tr class="commit <?php echo $zebra?>">
				<td>
					<?php echo $chaw->commit($commit['Repo']['revision'], (array)$CurrentProject);?>
				</td>
				<td class="message">
					<?php echo $commit['Repo']['message'];?>
				</td>
				<td>
					<?php echo $commit['Repo']['author'];?>
				</td>
				<td>
					<?php echo $commit['Repo']['commit_date'];?>
				</td>
			</tr>

		<?php endforeach;?>
	</tbody>
</table>
</div>

<div class="paging">
<?php
	$paginator->options(array('url' => $this->passedArgs));
	echo $paginator->prev('<< ' . __('previous', true));
	echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
	echo $paginator->next(__('next', true) . ' >>');
?>
</div>
