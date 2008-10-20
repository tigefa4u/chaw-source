<?php
$html->css('highlight/idea', null, false);
$javascript->link('highlight', false);

$script = '
hljs.initHighlightingOnLoad("diff");
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	$(".message").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>

<h2>Commit <?php echo $commit['Commit']['revision'];?></h2>

<div class="commit view">

	<p>
		<strong>Author:</strong> <?php echo $commit['Commit']['author'];?>
	</p>

	<p>
		<strong>Date:</strong> <?php echo $commit['Commit']['commit_date'];?>
	</p>

	<p class="message">
		<?php echo $commit['Commit']['message'];?>
	</p>

	<?php if(!empty($commit['Commit']['changes'])):?>
	<p>
		<strong>Changes:</strong>
		<ul>
		<?php
			foreach (unserialize($commit['Commit']['changes']) as $changed) :
				echo $html->tag('li', $changed);
			endforeach;

		?>
		</ul>
	</p>
	<?php endif?>

	<?php
		echo (!empty($commit['Commit']['diff'])) ? $html->tag('pre', $html->tag('code', $commit['Commit']['diff'], array('class' => 'diff'))) : null;
	?>
</div>