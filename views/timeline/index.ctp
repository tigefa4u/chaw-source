<?php
$script = '
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	$(".summary").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>

<h2>Timeline</h2>

<div class="timeline index">
	<?php foreach ((array)$timeline as $event):?>

		<div class="timeline">

			<p class="summary">
				<?php echo $event['Timeline']['summary'];?>
			</p>

		</div>

	<?php endforeach;?>

	<?php
		echo $paginator->prev();
		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
		echo $paginator->next();
	?>

</div>