<?php
class Timeline extends AppModel {

	var $name = 'Timeline';

	var $useTable = 'timeline';

	var $belongsTo = array(
		'Commit' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model' => 'Commit')
		),
		'Ticket' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model' => 'Ticket')
		),
		'Wiki' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model' => 'Wiki')
		)
	);
}
?>