<?php
$this->set('showdown', true);
$html->script('jquery.highlight_diff.min', array('inline' => false));
$script = '
$(document).ready(function(){
	$(".diff").highlight_diff();
});
';
$html->scriptBlock($script, array('inline' => false));
?>
<h2>
	Commit: <?php echo $commit['Commit']['revision'];?>
</h2>
<div class="nav tabs right">
	<ul>
		<li><?php echo $html->link(__('All Commits',true), array('controller' => 'commits', 'action' => 'index'));?></li>
		<li>
		<?php
		$branchLinks = array();
		foreach((array)$branches as $branch) :
			$branchLinks[] = $html->link($branch, $chaw->url((array)$CurrentProject, array(
				'controller' => 'commits', 'action' => 'branch', $branch
			)));
		endforeach;
			echo implode("</li>\n<li>", $branchLinks);
		?>
		</li>
</div>

<h2>

</h2>


<div class="commit view">
	<div>
		<strong><?php  __('Author') ?>:</strong> <?php echo $commit['Commit']['author'];?> | <strong><?php __('Date') ?>:</strong> <?php echo $commit['Commit']['commit_date'];?>
	</div>

	<div class="message wiki-text markdown">
		<?php echo h($commit['Commit']['message']);?>
	</div>

	<?php if(!empty($commit['Commit']['changes']) && is_array($commit['Commit']['changes'])):?>
	<p>
		<strong><?php __('Changes') ?>:</strong>
		<ul>
		<?php
			foreach ($commit['Commit']['changes'] as $changed) :
				echo $html->tag('li', $changed);
			endforeach;

		?>
		</ul>
	</p>
	<?php endif?>
	<div class="diff">
		<?php echo h($commit['Commit']['diff']);?>
	</div>
	<?php
		//pr($commit['Commit']['diff']);
		//echo $diff->render($commit['Commit']['diff']);
		//echo (!empty($commit['Commit']['diff'])) ? $html->tag('pre', $html->tag('code', h($commit['Commit']['diff']), array('class' => 'diff'))) : null;
	?>
</div>