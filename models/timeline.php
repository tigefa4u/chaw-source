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
	
	var $_findMethods = array('events' => true);

	var $actsAs = array('Containable');

	var $validate = array(
		'model' => array('notEmpty'),
		'foreign_key' => array('numeric')
	);
	
	var $belongsTo = array(
		'Project',
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
	
	function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$this->unbindModel(array('belongsTo' => array(
			'Comment', 'Ticket', 'Wiki', 'Commit',
		)), false);
		return $this->find('count', compact('conditions'));
	}
	
	function paginate($conditions = array(), $fields = array(), $order = array(), $limit = null, $page = null, $recursive = 0, $extra = array()) {
		return $this->find('events', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive'));
	}

	function _findEvents($state, $query, $results = array()) {
		if ($state == 'before') {
			$defaults = array(
				'order' => array(
					'Timeline.created' => 'DESC',
					'Timeline.id' => 'DESC'
				)
			);
			return Set::merge($defaults, $query);
		}
		
		$data = $branches = $branch = array();
		foreach ((array)$results as $key => $timeline) {
			$type = $timeline['Timeline']['model'];
			if (!isset($this->{$type})) {
				continue;
			}
			
			$this->{$type}->recursive = 0;
			
			if ($type == 'Comment') {
				$this->{$type}->recursive = 2;
				$this->{$type}->Ticket->unbindModel(array(
					'hasMany' => array('Comment'),
				));
			}
			
			$related = $this->{$type}->findById($timeline['Timeline']['foreign_key']);
			
			if (!empty($related)) {
				$data[] = array_merge($timeline, (array)$related);
			}
		}
		return $data;
	}

	// function beforeSave() {
	// 		if (!empty($this->data['Timeline']['model']) && !empty($this->data['Timeline']['foreign_key'])) {
	// 			$this->recursive = -1;
	// 			$id = $this->field('id', array(
	// 				'model' => $this->data['Timeline']['model'],
	// 				'foreign_key' => $this->data['Timeline']['foreign_key']
	// 			));
	// 			if (!$id || ($id && $this->id == $id)) {
	// 				return true;
	// 			}
	// 		}
	// 		return false;
	// 	}
}
?>