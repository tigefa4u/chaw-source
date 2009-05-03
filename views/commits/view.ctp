<?php
$this->set('showdown', true);
$html->css('highlight/idea', null, null, false);
$javascript->link('jquery.highlight_diff.min', false);
$script = '
$(document).ready(function(){
	$(".diff").highlight_diff();
});
';
$javascript->codeBlock($script, array('inline' => false));
?>
<div class="page-navigation">
	<?php echo $html->link(__('All Commits',true), array('controller' => 'commits', 'action' => 'index'));?>
</div>

<h2>
	<?php echo $commit['Commit']['revision'];?>
</h2>


<div class="commit view">
	<p>
		<strong><?php  __('Author') ?>:</strong> <?php echo $commit['Commit']['author'];?>
	</p>

	<p>
		<strong><?php __('Date') ?>:</strong> <?php echo $commit['Commit']['commit_date'];?>
	</p>

	<p class="message wiki-text">
		<?php echo h($commit['Commit']['message']);?>
	</p>

	<?php if(!empty($commit['Commit']['changes'])):?>
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