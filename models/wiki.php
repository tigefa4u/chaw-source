<?php
class Wiki extends AppModel {

	var $name = 'Wiki';

	var $useTable = 'wiki';

	var $validate = array('content' => array('notEmpty'));

	var $belongsTo = array(
		'User' => array(
			'foreignKey' => 'last_changed_by'
		)
	);

	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('model' => 'Wiki')
		)
	);

	function beforeSave(){
		if (!empty($this->data['Wiki']['title'])) {
			$this->data['Wiki']['slug'] = Inflector::slug($this->data['Wiki']['title']);
		}
		$this->recursive = -1;
		$this->updateAll(array('Wiki.active' => 0), array(
			'Wiki.slug' => $this->data['Wiki']['slug'],
			'Wiki.project_id' => $this->data['Wiki']['project_id']
		));

		$this->data['Wiki']['active'] = 1;

		return true;
	}

	function afterSave($created) {

		$this->User->id = $this->data['Wiki']['last_changed_by'];
		$username = $this->User->field('username');

		$summary = "###Wiki: [wiki:" . $this->data['Wiki']['slug'] . "]"
			. "\n\n__Author:__ " . $username
			. "\n\n__Date:__ " . $this->data['Wiki']['created'];

		$timeline = array('Timeline' => array(
			'project_id' => $this->data['Wiki']['project_id'],
			'model' => 'Wiki',
			'foreign_key' => $this->id,
			'summary' => $summary,
		));

		$this->Timeline->create($timeline);

		$this->Timeline->save();
	}

}
?>