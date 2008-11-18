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

<div class="timeline index">
<h2>Timeline</h2>
	<?php
		foreach ((array)$timeline as $event):
			$type = $event['Timeline']['model'];
			echo $this->element('timeline/' . strtolower($type), array('data' => $event[$type]));
		endforeach;
	?>
</div>
<div class="paging">
	<?php
		echo $paginator->prev();
				
		echo $paginator->numbers(array(
			'before' => ' | ', 'after' => ' | '
		));
				
		echo $paginator->next();
	?>
</div>