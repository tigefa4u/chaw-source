<?php
/**
 * Short description
 *
 * Long description
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.models
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class Timeline extends AppModel {

	var $name = 'Timeline';

	var $useTable = 'timeline';

	var $actsAs = array('Containable');

	var $belongsTo = array(
		'Comment' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Comment\''),
			'dependent' => true
		),
		'Commit' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Commit\''),
			'dependent' => true
		),
		'Ticket' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Ticket\''),
			'dependent' => true
		),
		'Wiki' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Wiki\''),
			'dependent' => true
		)
	);

	function related(&$data) {
		foreach ($data as $key => $timeline) {
			$type = $timeline['Timeline']['model'];
			$this->{$type}->recursive = 0;

			if ($type == 'Comment') {
				$this->{$type}->recursive = 2;
				$this->{$type}->Ticket->unbindModel(array(
					'hasMany' => array('Comment'),
				));
			}

			$related = $this->{$type}->findById($timeline['Timeline']['foreign_key']);
			$data[$key] = array_merge($timeline, (array)$related);
		}
		return $data;
	}
}
?>