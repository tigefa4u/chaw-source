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

	var $_findMethods = array('superList' => true);
/*
	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Wiki\'')
		)
	);
*/
	function slug($string) {
		$replace = (strpos($string, '-') !== false) ? '-' : '_';
		return Inflector::slug($string, $replace);
	}

	function _findSuperList($state, $query, $results = array()) {
		if ($state == 'before') {
			return $query;
		}

		if ($state == 'after') {
			if(!isset($query['separator'])) {
				$query['separator'] = ' ';
			}
			for($i = 0; $i <= 2; $i++) {
				if (strpos($query['fields'][$i], '.') === false) {
					$query['fields'][$i] = $this->alias . '/' . $query['fields'][$i];
				} else {
					$query['fields'][$i] = str_replace('.', '/', $query['fields'][$i]);
				}
			}
			return Set::combine($results, '/'.$query['fields'][0], array(
					'%s' . $query['separator'] . '%s',
					'/' . $query['fields'][1],
					'/' . $query['fields'][2]
			));
			return $results;
		}
	}

	function beforeSave(){
		if (!empty($this->data['Wiki']['title'])) {
			$this->data['Wiki']['slug'] = $this->slug($this->data['Wiki']['title']);
		}

		if (empty($this->data['Wiki']['slug'])) {
			return false;
		}

		if (!empty($this->data['Wiki']['active'])) {
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

		return true;
	}

	function afterSave($created) {
		$Timeline = ClassRegistry::init('Timeline');

		if ($created && !empty($this->data['Wiki']['active'])) {
			$timeline = array('Timeline' => array(
				'project_id' => $this->data['Wiki']['project_id'],
				'model' => 'Wiki',
				'foreign_key' => $this->id,
			));

			$Timeline->create($timeline);
			$Timeline->save();
		}
	}

	function activate($data = array()) {
		$this->set($data);
		$this->data['Wiki']['active'] = 1;
		return $this->save();
	}
}
?>