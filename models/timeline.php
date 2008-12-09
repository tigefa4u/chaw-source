<?php
/**
 * Short description
 *
 * Long description
 *
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Licensed under The MIT License
 * Redistributions of files must retain the copyright notice.
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.models
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
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

	function related(&$data) {
		foreach ($data as $key => $timeline) {
			$type = $timeline['Timeline']['model'];
			$this->{$type}->recursive = 0;
			$related = $this->{$type}->findById($timeline['Timeline']['foreign_key']);
			$data[$key] = array_merge($timeline, (array)$related);
		}
		return $data;
	}
}
?>