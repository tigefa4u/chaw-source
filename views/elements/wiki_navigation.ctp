<?php
	$nav = null;
?>

<?php if (!empty($wikiNav)):?>
	<?php
		foreach ($wikiNav as $category):
			$nav .= $html->tag('li',
				$html->link(ltrim($category, '/'), array($category))
			);
		endforeach;
	?>
<?php endif;?>
<?php if (!empty($subNav)):?>
	<?php
		foreach ($subNav as $subpage):
			$title = ltrim($subpage['Wiki']['path'] . '/' . $subpage['Wiki']['slug'], '/');
			$nav .= $html->tag('li',
				$html->link($title, array($subpage['Wiki']['path'], $subpage['Wiki']['slug']))
			);
		endforeach;
	?>
<?php endif;?>

<?php
if (!empty($nav)) {
	echo $html->tag('ul', $nav);
}
?>