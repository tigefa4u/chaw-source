<?php
class Timeline extends AppModel {

	var $name = 'Timeline';

	var $useTable = 'timeline';

	var $actsAs = array('Containable');

	var $belongsTo = array(
		'Comment' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Comment\'')
		),
		'Commit' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Commit\'')
		),
		'Ticket' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Ticket\'')
		),
		'Wiki' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Wiki\'')
		)
	);
}
?>