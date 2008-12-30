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

	var $actsAs = array('Containable', 'List' => array('position_column' => 'number', 'scope' => 'project_id'));

	var $belongsTo = array(
		'Project',
		'Version',
		'Owner' => array('className' => 'User', 'foreignKey' => 'Owner'),
		'Reporter' => array('className' => 'User', 'foreignKey' => 'reporter'),
	);
/*
	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Ticket\'')
		)
	);
*/
	var $hasMany = array('Comment');

	var $hasAndBelongsToMany = array('Tag');

	var $validate = array(
		'title' => array('notEmpty'),
		'description' => array('notEmpty'),
		'project_id' => 'numeric'
	);

	function beforeValidate() {
		if (!empty($this->data['Ticket']['project'])) {
			$this->data['Ticket']['project_id'] = $this->Project->field('id', array('url' => $this->data['Ticket']['project']));
		}
		return true;
	}

	function beforeSave() {
		$owner = null;
		if (!empty($this->data['Ticket']['owner']) && !is_numeric($this->data['Ticket']['owner'])) {
			$owner = $this->data['Ticket']['owner'];
			$this->data['Ticket']['owner'] = $this->Owner->field('id', array('username' => $owner));
		}
		if (!empty($this->data['Ticket']['tags'])) {
			if (empty($this->data['Ticket']['previous']) || !empty($this->data['Ticket']['previous']) && $this->data['Ticket']['tags'] != $this->data['Ticket']['previous']['tags']) {
				$this->data['Tag']['Tag'] = $this->Tag->generate($this->data['Ticket']['tags']);
			}
		}

		if (empty($this->data['Ticket']['title']) && empty($this->data['Ticket']['comment']) && empty($this->data['Ticket']['status'])) {
			return false;
		}

		if ($this->id && (!empty($this->data['Ticket']['comment']) || !empty($this->data['Ticket']['description']))) {
			$changes = array();

			foreach((array)$this->data['Ticket']['previous'] as $field => $previous) {
				if (in_array($field, array('id', 'created', 'modified'))) {
					continue;
				}

				if (isset($this->data['Ticket'][$field]) && $previous != $this->data['Ticket'][$field]) {
					$change = "- **" . $field . "** was changed\n";
					if ($field !== 'description' && !empty($this->data['Ticket'][$field])) {
						if ($field == 'owner' && $owner) {
							$change .= "_" . $owner . "_";
						} else {
							$change .= "_" . $this->data['Ticket'][$field] . "_";
						}
					}
					$changes[] = $change;
				}
			}

			if (!empty($changes) || !empty($this->data['Ticket']['comment'])) {
				$data = array('Comment' => array(
					'ticket_id' => $this->id,
					'project_id' => $this->data['Ticket']['project_id'],
					'user_id' => $this->data['Ticket']['user_id'],
					'body' => join("\n", $changes) ."\n\n" . $this->data['Ticket']['comment']
				));

				$this->Comment->create($data);
				return $this->Comment->save();
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