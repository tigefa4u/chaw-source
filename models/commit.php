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
class Commit extends AppModel {

	var $name = 'Commit';

	var $belongsTo = array('User', 'Project');

	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('model' => 'Commit'),
			'dependent' => true
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
				'model' => 'Commit',
				'foreign_key' => $this->id,
			));

			$this->Timeline->create($timeline);
			$this->Timeline->save();
		}
	}

	function findByRevision($revision) {
		$commit = parent::findByRevision($revision);
		$data = $this->Project->Repo->read($revision);
		if (!empty($commit)) {
			$commit['Commit'] = array_merge($commit['Commit'], $data);
		} else {
			$commit['Commit'] = $data;
		}
		return $commit;
	}
}
?>