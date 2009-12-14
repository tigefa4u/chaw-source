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
class Wiki extends AppModel {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Wiki';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $displayField = 'slug';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $useTable = 'wiki';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $actsAs = array('Directory');

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $validate = array(
		'slug' => array(
			'required' => true,
			'rule' => 'notEmpty'
		),
		'content' => array('notEmpty')
	);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $belongsTo = array(
		'User' => array(
			'foreignKey' => 'last_changed_by'
		),
		'Project'
	);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $_findMethods = array('superList' => true);

/*
	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Wiki\'')
		)
	);
*/
	/**
	 * undocumented function
	 *
	 * @param string $string
	 * @return void
	 */
	function slug($string) {
		$replace = (strpos($string, '-') !== false) ? '-' : '_';
		return Inflector::slug($string, $replace);
	}

	/**
	 * undocumented function
	 *
	 * @param string $state
	 * @param string $query
	 * @param string $results
	 * @return void
	 */
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
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeValidate(){
		if (!empty($this->data['Wiki']['title'])) {
			$this->data['Wiki']['slug'] = $this->data['Wiki']['title'];
		}
		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeSave(){
		$this->data['Wiki']['slug'] = $this->slug($this->data['Wiki']['slug']);

		if (!empty($this->data['Wiki']['active'])) {
			$this->recursive = -1;
			$this->updateAll(array(
					'Wiki.active' => 0,
					'Wiki.modified' => "'" . date('Y-m-d H:i:s') . "'"
				),
				array(
				'Wiki.slug' => $this->data['Wiki']['slug'],
				'Wiki.path' => $this->data['Wiki']['path'],
				'Wiki.project_id' => $this->data['Wiki']['project_id'],
			));
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
		if ($created && $this->addToTimeline && !empty($this->data['Wiki']['active'])) {
			$Timeline = ClassRegistry::init('Timeline');
			$timeline = array('Timeline' => array(
				'project_id' => $this->data['Wiki']['project_id'],
				'model' => 'Wiki',
				'foreign_key' => $this->id,
			));

			$Timeline->create($timeline);
			$Timeline->save();
		}
	}

	/**
	 * undocumented function
	 *
	 * @param string $data
	 * @return void
	 */
	function activate($data = array()) {
		$this->set($data);
		$this->data['Wiki']['active'] = 1;
		return $this->save();
	}
}
?>