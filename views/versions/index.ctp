<?php
$script = '
$(document).ready(function(){
	$(".summary").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>
<?php if (!empty($this->params['isAdmin'])): ?>
<div class="page-navigation">
	<?php echo $chaw->admin(__('New Version',true), array('admin' => true, 'controller' => 'versions', 'action' => 'add'));?>
</div>
<?php endif; ?>

<div class="versions index">
	<?php foreach ((array)$versions as $version):?>

		<div class="version">
			<h3>
				<?php echo $version['Version']['title'];?>
				<em>
					<?php echo $chaw->admin('edit', array('admin' => true, 'controller' => 'versions', 'action' => 'edit', $version['Version']['id']));?>
				</em>
			</h3>


			<p class="date">
				<strong><?php __('Created') ?>:</strong> <?php echo date('Y-m-d', strtotime($version['Version']['created']));?>

				<?php if (empty($version['Version']['completed'])): ?>
					<strong><?php __('Due by') ?>:</strong> <?php echo $version['Version']['due_date'];?>
				<?php endif; ?>

			</p>

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