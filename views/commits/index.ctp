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

<div class="commits timeline index">
<?php $i = 0;
	foreach ((array)$commits as $commit):
		$zebra = ($i++ % 2) == 0 ? 'zebra' : null;
		if (!empty($commit['Commit']['revision'])) {
			echo $this->element('timeline/commit', array('data' => $commit, 'zebra' => $zebra));
		}
	endforeach;
?>
</div>
<div class="paging">
<?php
	echo $paginator->prev();
	echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
	echo $paginator->next();
?>
</div>