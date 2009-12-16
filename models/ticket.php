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
class Ticket extends AppModel {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Ticket';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $actsAs = array(
		'Containable',
		'List' => array('position_column' => 'number', 'scope' => 'project_id'),
		'StateMachine' => array(
			'field' => 'status',
			'default' => 'pending',
			'states' => array('pending', 'approved', 'in progress', 'on hold', 'closed'),
			'auto' => 'after',
			'transitions' => array(
				'approve' => array('pending' => 'approved'),
				'accept' => array('pending' => 'in progress', 'approved' => 'in progress'),
				'hold' => array(
					'pending' => 'on hold', 'approved' => 'on hold', 'in progress' => 'on hold'
				),
				'close' => array(
					'pending' => 'closed', 'approved' => 'closed', 'in progress' => 'closed',
					'on hold' => 'closed'
				),
				'reopen' => array('closed' => 'pending', 'on hold' => 'pending')
			)
		)
	);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $belongsTo = array(
		'Project', 'Version',
		'Owner' => array('className' => 'User', 'foreignKey' => 'Owner'),
		'Reporter' => array('className' => 'User', 'foreignKey' => 'reporter'),
	);

	// var $hasOne = array(
	// 	'Timeline' => array(
	// 		'foreignKey' => 'foreign_key',
	// 		'conditions' => array('Timeline.model = \'Ticket\'')
	// 	)
	// );

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $hasMany = array(
		'Comment' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Comment.model = "Ticket"'),
			'order' => 'Comment.created ASC'
		)
	);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $hasAndBelongsToMany = array('Tag');

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $validate = array(
		'project_id' => array('numeric'),
		'title' => array('notEmpty'),
		'description' => array('notEmpty'),
		// 'comment' => array('notEmpty'),
		// 'status' => array('notEmpty')
		// 'event' => array('notEmpty'),
		// 'resolution' => array('notEmpty'),
	);

	/**
	 * undocumented function
	 *
	 * @param string $event
	 * @return void
	 */
	function transitions($event) {
		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeValidate() {
		if (!empty($this->data['Ticket']['project'])) {
			$this->data['Ticket']['project_id'] = $this->Project->field('id', array(
				'url' => $this->data['Ticket']['project']
			));
		}
		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeSave() {
		if (
			empty($this->data['Ticket']['title'])
			&& empty($this->data['Ticket']['comment'])
			&& empty($this->data['Ticket']['status'])
			&& empty($this->data['Ticket']['event'])
			&& empty($this->data['Ticket']['resolution'])
		) {
			return false;
		}

		if (!empty($this->data['Ticket']['tags'])) {
			if (empty($this->data['Ticket']['previous']) || !empty($this->data['Ticket']['previous']) && $this->data['Ticket']['tags'] != $this->data['Ticket']['previous']['tags']) {
				$this->data['Tag']['Tag'] = $this->Tag->generate($this->data['Ticket']['tags']);
			}
		}

		$reason = null;

		if (!empty($this->data['Ticket']['resolution'])) {
			$reason = $this->data['Ticket']['resolution'];
			$this->data['Ticket']['event'] = 'close';
			$this->data['Ticket']['owner'] = !empty($this->data['Ticket']['user_id']) ? $this->data['Ticket']['user_id'] : 0;
		}

		if (!empty($this->data['Ticket']['event'])) {
			if ($this->event($this->data['Ticket']['event'])) {
				if ($this->data['Ticket']['event'] == 'accept') {
					$this->data['Ticket']['owner'] = $this->data['Ticket']['user_id'];
				} elseif ($this->data['Ticket']['event'] == 'reopen') {
					$reason = 'reopened';
					$this->data['Ticket']['resolution'] = null;
				}
			}
		}

		$owner = null;
		if (isset($this->data['Ticket']['owner'])) {
			if (!is_numeric($this->data['Ticket']['owner'])) {
				$owner = $this->data['Ticket']['owner'];
				$this->data['Ticket']['owner'] = $this->Owner->field('id', array('username' => $owner));
			} elseif (!empty($this->data['Ticket']['owner'])) {
				$owner = $this->Owner->field('username', array('id' => $this->data['Ticket']['owner']));
			}
		}

		$version = false;
		if (!empty($this->data['Ticket']['version_id'])) {
			$version = $this->Version->field('title', array('id' => $this->data['Ticket']['version_id']));
		} else {
			unset($this->data['Ticket']['version_id']);
		}

		if ($this->id) {
			$changes = array();
			if (isset($this->data['Ticket']['previous'])) {
				$previous = $this->data['Ticket']['previous'];
				unset($this->data['Ticket']['previous']);

				foreach ((array)$previous as $field => $value) {
					if (array_key_exists($field, $this->data['Ticket']) && $this->data['Ticket'][$field] != $value) {
						if (in_array($field, array('created', 'modified'))) {
							continue;
						}
						$change = null;
						if ($field == 'description') {
							$change = "{$field}:";
						} elseif ($field == 'owner') {
							$change = "owner:{$owner}";
						} elseif ($field == 'version_id') {
							$change = "version:{$version}";
						} else {
							$change = "{$field}:{$this->data['Ticket'][$field]}";
						}
						if (isset($change)) {
							$changes[] = $change;
						}
					}
				}
			}
			if (!empty($this->data['Ticket']['comment'])) {
				$this->data['Ticket']['comment'] = trim($this->data['Ticket']['comment']);
			}

			if (!empty($changes) || !empty($this->data['Ticket']['comment'])) {
				$this->Comment->addToTimeline = $this->addToTimeline;
				$data = array('Comment' => array(
					'model' => 'Ticket',
					'foreign_key' => $this->id,
					'project_id' => $this->data['Ticket']['project_id'],
					'user_id' => $this->data['Ticket']['user_id'],
					'body' => $this->data['Ticket']['comment'],
					'changes' => join("\n", $changes),
					'reason' => $reason,
				));
				$this->Comment->create($data);
				$this->Comment->save();
			}
		}

		return true;
	}

	/**
	 * undocumented function
	 *
	 * @param string $created
	 * @return void
	 */
	function afterSave($created) {
		if ($created && $this->addToTimeline) {
			$Timeline = ClassRegistry::init('Timeline');
			$timeline = array('Timeline' => array(
				'project_id' => $this->data['Ticket']['project_id'],
				'model' => 'Ticket',
				'foreign_key' => $this->id,
			));

			$Timeline->create($timeline);
			$Timeline->save();
		}
	}
}
?>