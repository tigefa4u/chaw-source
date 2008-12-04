<?php
$html->css('highlight/idea', null, null, false);
$javascript->link('highlight', false);

$script = '
hljs.initHighlightingOnLoad();

$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	$(".wiki").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>
<div class="page-navigation">
	<?php echo $html->link('Edit', array('controller' => 'wiki', 'action' => 'add', $path, $slug));?>
	|
	<?php echo $html->link('New', array('controller' => 'wiki', 'action' => 'add', $path, $slug, 1));?>
</div>
<?php if(!empty($wiki)):?>
	<div class="wiki-content">
		<?php if(!empty($wiki) && !empty($page)):?>
			<h2><?php echo Inflector::humanize($slug);?></h2>
			<p><?php echo h($page['Wiki']['content']);?></p>
		<?php endif;?>
		<?php foreach($wiki as $content):?>
			<h3><?php echo $html->link(Inflector::humanize($content['Wiki']['slug']), array('controller' => 'wiki', 'action' => 'index', $content['Wiki']['path'], $content['Wiki']['slug']));?></h3>
			<div class="wiki view">				
				<?php echo h($text->truncate($content['Wiki']['content'], 100, '...', false, true)); ?>
			</div>
			<?php echo $html->link('View', array('controller' => 'wiki', 'action' => 'index', $content['Wiki']['path'], $content['Wiki']['slug']));?>
			|
			<?php echo $html->link('Edit', array('controller' => 'wiki', 'action' => 'add', $content['Wiki']['path'], $content['Wiki']['slug']));?>
			|
			<?php echo $html->link('New', array('controller' => 'wiki', 'action' => 'add', $content['Wiki']['path'], $content['Wiki']['slug'], 1));?>
		<?php endforeach; ?>
	</div>
<?php elseif(!empty($page)):?>	
	<div class="wiki wiki-content">
		<?php echo h($page['Wiki']['content']); ?>
	</div>
<?php endif?>

<div class="wiki-side">
	<?php
	if (!empty($sub)):
		$nav = null;
		foreach ($sub as $category):
			if (str_replace($slug, '', $category) !== '/') :
				$nav .= $html->tag('li',
					$html->link($category, array($category))
				);
			endif;
		endforeach;
		echo $html->tag('div', $html->tag('ul', $nav), array('class' => 'wiki-sub'));
	endif;
	?>
</div>