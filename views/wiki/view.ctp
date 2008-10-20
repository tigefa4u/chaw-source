<?php
$script = '
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	$("#WikiContent").html(converter.makeHtml(jQuery.trim($("#WikiContent").text())));
});
';
$javascript->codeBlock($script, array('inline' => false));
?>
<div class="page-navigation">
	<?php echo $html->link('New', array('controller' => 'wiki', 'action' => 'add'));?>
	|
	<?php echo $html->link('Edit', array('controller' => 'wiki', 'action' => 'edit', $wiki['Wiki']['slug']));?>
</div>
<div id="WikiContent" class="wiki view">
	<?php echo $html->clean($wiki['Wiki']['content']); ?>
</div>
