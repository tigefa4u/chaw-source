<?php
$this->set('showdown', true);
$html->css('highlight/idea', null, null, false);
$javascript->link('highlight.pack', false);
$script = 'hljs.initHighlightingOnLoad();';
$javascript->codeBlock($script, array('inline' => false));
?>
<div class="nav tabs right">
	<ul>
	<?php if (!empty($canWrite)):?>

		<?php if (!empty($page['Wiki']['active'])):?>
			<li class="active-wiki"><?php __('Active') ?></li>
		<?php else: ?>
			<li class="inactive-wiki"><?php __('Not Active') ?></li>
		<?php endif;?>

		<?php if ((empty($content['Wiki']['read_only']) || $CurrentUser->id == $page['Wiki']['last_changed_by'])):?>
			<li><?php echo $html->link(__('Edit',true), array('controller' => 'wiki', 'action' => 'edit', $path, $slug));?></li>
			<li><?php echo $html->link(__('New',true), array('controller' => 'wiki', 'action' => 'add', $path, 'new-page'));?></li>
		<?php endif;?>

	<?php endif;?>
	</ul>
</div>

<div class="clear"><!----></div>

<?php if (!empty($page)): ?>
	<div class="wiki-content">
		<div class="wiki-text">
			<?php echo h($page['Wiki']['content']);?>
		</div>
	</div>

<?php endif; ?>

<?php if (empty($page) && !empty($wiki)): ?>
	<div class="wiki-content">

		<?php foreach($wiki as $content):
			$data = h($text->truncate($content['Wiki']['content'], 420, '...', false, true));
		?>
			<?php if (strpos($data, '##') === false):?>
				<h3><?php
					echo $html->link(Inflector::humanize($content['Wiki']['slug']), array(
						'controller' => 'wiki', 'action' => 'index',
						$content['Wiki']['path'], $content['Wiki']['slug']
					));?>
				</h3>
			<?php endif; ?>

			<div class="wiki-text">
				<?php echo $data; ?>
			</div>

			<div class="actions">
				<?php echo $html->link(__('View',true), array(
						'controller' => 'wiki', 'action' => 'index',
						$content['Wiki']['path'], $content['Wiki']['slug']));
				?>
				<?php if (!empty($canWrite) && (empty($content['Wiki']['read_only']) || $CurrentUser->id == $content['Wiki']['last_changed_by'])):?>
					|
					<?php echo $html->link(__('Edit',true), array(
							'controller' => 'wiki', 'action' => 'edit',
							$content['Wiki']['path'], $content['Wiki']['slug']));
					?>
					|
					<?php echo $html->link(__('New',true), array(
							'controller' => 'wiki', 'action' => 'add',
							$content['Wiki']['path'], 'new-page'));
					?>
				<?php endif; ?>
			</div>

		<?php endforeach; ?>

	</div>
<?php endif; ?>

<?php if (empty($revisions) && !empty($page)):?>
<div class="wiki-footer">
	<p class="author">
		last revision by
		<strong><?php echo $page['User']['username']?></strong>
		on <?php echo date('Y-m-d', strtotime($page['Wiki']['created']));?>
	</p>
</div>
<?php endif;?>

<?php if (!empty($revisions) && !empty($page)):?>
<div class="wiki-footer revisions">
	<?php echo $form->create(array('url' => array('action' => 'index', $path, $slug))); ?>
	<div class="revision" title="Revision"><?php echo $form->input('revision', array('value' => $page['Wiki']['id'])); ?></div>
	<?php
		$buttons =
			$form->submit(__('view',true), array('div' => false, 'name' => 'view'))
			. $form->submit(__('activate',true), array('div' => false, 'name' => 'activate'));
		if (!empty($canDelete)) {
			$buttons .= $form->submit(__('delete',true), array('div' => false, 'name' => 'delete'));
		}
		echo $html->tag('div', $buttons, array('class' => 'submit'));
		echo $form->end();
	?>
</div>
<?php endif;?>
