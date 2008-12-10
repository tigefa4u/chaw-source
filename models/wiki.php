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
class Wiki extends AppModel {

	var $name = 'Wiki';

	var $displayField = 'slug';

	var $useTable = 'wiki';

	var $actsAs = array('Directory');

	var $validate = array(
		'title' => array('notEmpty'),
		'content' => array('notEmpty')
	);

	var $belongsTo = array(
		'User' => array(
			'foreignKey' => 'last_changed_by'
		),
		'Project'
	);
/*
	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Wiki\'')
		)
	);
*/
	function beforeSave(){
		if (!empty($this->data['Wiki']['title'])) {
			$this->data['Wiki']['slug'] = Inflector::slug($this->data['Wiki']['title']);
		}

		if ($this->id) {
			$this->recursive = -1;
			$this->updateAll(array(
					'Wiki.active' => 0,
					'Wiki.modified' => "'" . date('Y-m-d H:m:s') . "'"
				),
				array(
				'Wiki.slug' => $this->data['Wiki']['slug'],
				'Wiki.path' => $this->data['Wiki']['path'],
				'Wiki.project_id' => $this->data['Wiki']['project_id'],
			));
		}
		$this->data['Wiki']['active'] = 1;

		return true;
	}

	function afterSave($created) {
		$Timeline = ClassRegistry::init('Timeline');

		if ($created) {
			$timeline = array('Timeline' => array(
				'project_id' => $this->data['Wiki']['project_id'],
				'model' => 'Wiki',
				'foreign_key' => $this->id,
			));

			$Timeline->create($timeline);
			$Timeline->save();
		}
	}

}
?>