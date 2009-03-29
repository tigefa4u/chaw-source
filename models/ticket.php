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
class Ticket extends AppModel {

	var $name = 'Ticket';

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

	var $belongsTo = array(
		'Project',
		'Version',
		'Owner' => array('className' => 'User', 'foreignKey' => 'Owner'),
		'Reporter' => array('className' => 'User', 'foreignKey' => 'reporter'),
	);

	// var $hasOne = array(
	// 	'Timeline' => array(
	// 		'foreignKey' => 'foreign_key',
	// 		'conditions' => array('Timeline.model = \'Ticket\'')
	// 	)
	// );

	var $hasMany = array(
		'Comment' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Comment.model = "Ticket"'),
			'order' => 'Comment.created ASC'
		)
	);

	var $hasAndBelongsToMany = array('Tag');

	var $validate = array(
		'title' => array('notEmpty'),
		'description' => array('notEmpty'),
		'project_id' => 'numeric'
	);

	function transitions($event) {
		return true;
	}

	function beforeValidate() {
		if (!empty($this->data['Ticket']['project'])) {
			$this->data['Ticket']['project_id'] = $this->Project->field('id', array(
				'url' => $this->data['Ticket']['project']
			));
		}
		return true;
	}

	function beforeSave() {
		if (empty($this->data['Ticket']['title']) && empty($this->data['Ticket']['comment']) && empty($this->data['Ticket']['status']) && empty($this->data['Ticket']['event'])) {
			return false;
		}

		if (!empty($this->data['Ticket']['tags'])) {
			if (empty($this->data['Ticket']['previous']) || !empty($this->data['Ticket']['previous']) && $this->data['Ticket']['tags'] != $this->data['Ticket']['previous']['tags']) {
				$this->data['Tag']['Tag'] = $this->Tag->generate($this->data['Ticket']['tags']);
			}
		}
		
		if (!empty($this->data['Ticket']['event'])) {
			if ($this->event($this->data['Ticket']['event'])) {
				if ($this->data['Ticket']['event'] == 'accept') {
					$this->data['Ticket']['owner'] = $this->data['Ticket']['user_id'];
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

				foreach ($this->data['Ticket'] as $field => $value) {
					if ($field == 'comment' || $field == 'modified') {
						continue;
					}
					if (isset($previous[$field]) && $previous[$field] != $value) {
						$change = null;
						if ($field == 'description') {
							$change = "- **{$field}** was changed";
						} elseif ($field == 'owner') {
							$change = "- **owner** was changed to _{$owner}_";
							if (empty($owner)) {
								$change = "- **owner** was removed";
							}
						} elseif ($field == 'version_id') {
							$change = "- **version** was changed to _{$version}_";
							if (empty($version)) {
								$change = "- **version** was removed";
							}
						} elseif (empty($value)) {
							$change = "- **{$field}** was removed";
						} else {
							$change = "- **{$field}** was changed to _{$value}_";
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
				$data = array('Comment' => array(
					'model' => 'Ticket',
					'foreign_key' => $this->id,
					'project_id' => $this->data['Ticket']['project_id'],
					'user_id' => $this->data['Ticket']['user_id'],
					'body' => join("\n", $changes) ."\n\n" . $this->data['Ticket']['comment']
				));

				$this->Comment->create($data);
				$this->Comment->save();
			}

			if (!empty($this->data['Ticket']['resolution'])) {
				$data = array('Resolution' => array(
					'model' => 'Ticket',
					'foreign_key' => $this->id,
					'project_id' => $this->data['Ticket']['project_id'],
					'user_id' => $this->data['Ticket']['user_id'],
				));

				$this->Resolution->create($data);
				$this->Resolution->save();
			}
		}

		return true;
	}

	function afterSave($created) {
		$Timeline = ClassRegistry::init('Timeline');

		if ($created) {
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