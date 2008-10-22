<?php
class Comment extends AppModel {

	var $name = 'Comment';

	var $belongsTo = array('User', 'Ticket');

	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('model' => 'Comment')
		)
	);

	function afterSave($created) {

		if ($created) {
			$timeline = array('Timeline' => array(
				'project_id' => $this->data['Comment']['project_id'],
				'model' => 'Comment',
				'foreign_key' => $this->id,
			));

			$this->Timeline->create($timeline);
			$this->Timeline->save();
		}
	}

}
?>