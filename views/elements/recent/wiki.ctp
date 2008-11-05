<?php
if (!empty($wiki)):

	$li = null;
	foreach ($wiki as $wik) :

		$li .= $html->tag('li',
			$html->link($wik['Wiki']['slug'], array('admin' => false,
				'controller' => 'wiki', 'action' => 'index', $wik['Wiki']['slug']
			)) . ' updated by ' . $wik['User']['username']
		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', 'Wiki Updates') .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;