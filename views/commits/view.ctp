<?php
$html->css('highlight/idea', null, null, false);
$javascript->link(array('ghighlight'), false);
?>
<div class="page-navigation">
	<?php echo $html->link('All Commits', array('controller' => 'commits', 'action' => 'index'));?>
</div>

<h2>
	<?php echo $commit['Commit']['revision'];?>
</h2>


<div class="commit view">
	<p>
		<strong>Author:</strong> <?php echo $commit['Commit']['author'];?>
	</p>

	<p>
		<strong>Date:</strong> <?php echo $commit['Commit']['commit_date'];?>
	</p>

	<p class="message wiki-text">
		<?php echo $commit['Commit']['message'];?>
	</p>

	<?php if(!empty($commit['Commit']['changes'])):?>
	<p>
		<strong>Changes:</strong>
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