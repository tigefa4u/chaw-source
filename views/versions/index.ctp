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

			<p>
				<?php echo $chaw->admin('edit', array('admin' => true, 'controller' => 'versions', 'action' => 'edit', $version['Version']['id']));?>
			</p>

			<p class="summary">
				<?php echo $version['Version']['description'];?>
			</p>
			<p class="created">
				<strong>Created:</strong> <?php echo date('Y-m-d', strtotime($version['Version']['created']));?>
			</p>
			<p class="created">
				<strong>Due by:</strong> <?php echo $version['Version']['due_date'];?>
			</p>
		</div>

	<?php endforeach;?>

	<?php
		echo $paginator->prev();
		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
		echo $paginator->next();
	?>

</div>
<div class="actions">
	<?php echo $chaw->admin('New Version', array('admin' => true, 'controller' => 'versions', 'action' => 'add'));?>
</div>