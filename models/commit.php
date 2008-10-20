<?php
class Commit extends AppModel {

	var $name = 'Commit';

	var $belongsTo = array('User');

	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('model' => 'Commit')
		)
	);

	function beforeSave() {
		if (!empty($this->data['Commit']['author'])) {
			$this->data['Commit']['user_id'] = $this->User->field('id', array('username' => $this->data['Commit']['author']));
		}
		if (!empty($this->data['Commit']['changes'])) {
			$this->data['Commit']['changes'] = serialize($this->data['Commit']['changes']);
		}
		return true;
	}

	function afterSave($created) {

		if ($created) {

			$timeline = array('Timeline' => array(
				'project_id' => $this->data['Commit']['project_id'],
				'model' => 'Wiki',
				'foreign_key' => $this->id,
			));

			if (!empty($this->data['Commit']['revision'])) {

				$changed = null;

				foreach (unserialize($this->data['Commit']['changes']) as $change) {
					$changed .= " * {$change}\n";
				}
				extract($this->data['Commit']);

				ob_start();
				include(CONFIGS . 'templates' . DS . 'commit_summary.ctp');
				$timeline['Timeline']['summary'] = ob_get_clean();

			}

			$this->Timeline->create($timeline);

			$this->Timeline->save();
		}
	}
}
?>