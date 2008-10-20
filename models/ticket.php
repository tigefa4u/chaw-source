<?php
class Ticket extends AppModel {

	var $name = 'Ticket';
	
	var $actsAs = array('Containable');

	var $belongsTo = array(
		'Version',
		'Owner' => array('className' => 'User', 'foreignKey' => 'Owner'),
		'Reporter' => array('className' => 'User', 'foreignKey' => 'reporter'),
	);
	
	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('model' => 'Ticket')
		)
	);
	
	var $hasMany = array('Comment');
	
	var $hasAndBelongsToMany = array('Tag');

	function beforeSave() {
		if (!empty($this->data['Ticket']['tags'])) {
			if (empty($this->data['Ticket']['previous']) || !empty($this->data['Ticket']['previous']) && $this->data['Ticket']['tags'] != $this->data['Ticket']['previous']['tags']) {
				$this->data['Tag']['Tag'] = $this->Ticket->Tag->generate($this->data['Ticket']['tags']);
			}
		}
		return true;
	}

	function afterSave($created) {

		if (!$created) {
			$comment = $this->data['Ticket']['comment'];
			unset($this->data['Ticket']['comment']);

			$changes = array();
			foreach((array)$this->data['Ticket']['previous'] as $field => $previous) {
				if (in_array($field, array('id', 'created', 'modified'))) {
					continue;
				}
				if (isset($this->data['Ticket'][$field]) && $previous !== $this->data['Ticket'][$field]) {
					if ($field === 'description') {
						$changes[] = "- __" . $field . "__ was changed\n";
					} else {
						$changes[] = "- __" . $field . "__ was changed to _" . $this->data['Ticket'][$field] . "_\n";
					}
				}
			}

			$data = array('Comment' => array(
				'ticket_id' => $this->id,
				'user_id' => $this->data['Ticket']['user_id'],
				'body' => join("\n", $changes) ."\n\n" . $comment
			));

			if (!empty($data['Comment']['body'])) {
				$this->Comment->create($data);
				$this->Comment->save();
			}
		}

		if (!empty($this->data['Ticket']['user_id'])) {
			$this->Reporter->id = $this->data['Ticket']['user_id'];
		} else {
			$this->Reporter->id = $this->data['Ticket']['reporter'];
		}

		$username = $this->Reporter->field('username');

		$summary = "###Ticket: #" . $this->id
			. "\n\n__Author:__ " . $username
			. "\n\n__Date:__ " . $this->data['Ticket']['modified'];

		$timeline = array('Timeline' => array(
			'project_id' => $this->data['Ticket']['project_id'],
			'model' => 'Ticket',
			'foreign_key' => $this->id,
			'summary' => $summary,
		));

		$this->Timeline->create($timeline);

		$this->Timeline->save();
	}
}
?>