<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
/**
 * undocumented class
 *
 * @package default
 */
class Timeline extends AppModel {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Timeline';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $useTable = 'timeline';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $_findMethods = array('events' => true);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $actsAs = array('Containable');

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $validate = array(
		'model' => array('notEmpty'),
		'foreign_key' => array('numeric')
	);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
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

	/**
	 * undocumented function
	 *
	 * @param string $conditions
	 * @param string $recursive
	 * @param string $extra
	 * @return void
	 */
	function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$this->unbindModel(array('belongsTo' => array(
			'Comment', 'Ticket', 'Wiki', 'Commit',
		)), false);
		return $this->find('count', compact('conditions'));
	}

	/**
	 * undocumented function
	 *
	 * @param string $conditions
	 * @param string $fields
	 * @param string $order
	 * @param string $limit
	 * @param string $page
	 * @param string $recursive
	 * @param string $extra
	 * @return void
	 */
	function paginate($conditions = array(), $fields = array(), $order = array(), $limit = null, $page = null, $recursive = 0, $extra = array()) {
		return $this->find('events', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive'));
	}

	/**
	 * undocumented function
	 *
	 * @param string $state
	 * @param string $query
	 * @param string $results
	 * @return void
	 */
	function _findEvents($state, $query, $results = array()) {
		if ($state == 'before') {
			$defaults = array(
				'order' => array(
					'Timeline.created' => 'DESC',
					'Timeline.id' => 'DESC'
				)
			);
			$query = Set::pushDiff($defaults, $query);
			return $query;
		}

		$data = array();
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
}
?>