<?php
$this->set('showdown', true);
$html->css('highlight/idea', null, null, false);
$javascript->link('ghighlight.min', false);
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
	<?php
		echo (!empty($commit['Commit']['diff'])) ? $html->tag('pre', $html->tag('code', h($commit['Commit']['diff']), array('class' => 'diff'))) : null;
	?>
</div>