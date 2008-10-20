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

<h2>Versions</h2>

<div class="versions index">
	<?php foreach ((array)$versions as $version):?>

		<div class="version">
			<h3>
				<?php echo $version['Version']['title'];?>
			</h3>
			<span class="created">
				<?php echo $version['Version']['created'];?>
			</span>

			<p class="summary">
				<?php echo $version['Version']['description'];?>
			</p>

		</div>

	<?php endforeach;?>

	<?php
		echo $paginator->prev();
		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
		echo $paginator->next();
	?>

</div>
<?php
	echo $html->link('New Version', array('admin' => true, 'controller' => 'versions', 'action' => 'add'));
?>