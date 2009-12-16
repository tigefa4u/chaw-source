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
class Commit extends AppModel {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Commit';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $validate = array(
		'project_id' => array('notEmpty'),
		'revision' => array('isUnique'),
		'author' => array('notEmpty'),
		'commit_date' => array('notEmpty'),
		'message' =>array('notEmpty'),

	);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $belongsTo = array(
		'User', 'Project'
	);
/*
	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Commit\''),
			'dependent' => true
		)
	);
*/
	/**
	 * undocumented function
	 *
	 * @param string $one
	 * @param string $two
	 * @return void
	 */
	function set($one = array(), $two = null) {
		parent::set($one, $two);
		if (!empty($this->data)) {
			$this->data['Commit'] = array_merge(array(
				'user_id'=> null, 'event' => 'committed', 'data' => 0,
			), $this->data['Commit']);
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeSave() {
		if (empty($this->data['Commit']['user_id']) && !empty($this->data['Commit']['author'])) {
			$this->data['Commit']['user_id'] = $this->User->field('id', array('username' => $this->data['Commit']['author']));
		}
		if (!empty($this->data['Commit']['changes']) && is_array($this->data['Commit']['changes'])) {
			$this->data['Commit']['changes'] = serialize($this->data['Commit']['changes']);
		}

		if (!empty($this->data['Commit']['branch'])) {
			$ref = explode('/', $this->data['Commit']['branch']);
			$this->data['Commit']['branch'] = array_pop($ref);
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
		if ($created && $this->addToTimeline) {
			$Timeline = ClassRegistry::init('Timeline');
			$timeline = array('Timeline' => array(
				'user_id' => $this->data['Commit']['user_id'],
				'project_id' => $this->data['Commit']['project_id'],
				'model' => 'Commit',
				'foreign_key' => $this->id,
				'event' => $this->data['Commit']['event'],
				'data' => $this->data['Commit']['data']
			));
			$Timeline->create($timeline);
			$Timeline->save();
		}
	}

	/**
	 * undocumented function
	 *
	 * @param string $revision
	 * @return void
	 */
	function findByRevision($revision) {
		$commit = parent::findByRevision($revision);
		$data = $this->Project->Repo->read($revision, true);
		if (!empty($commit)) {
			$commit['Commit'] = array_merge($commit['Commit'], $data);
		} else {
			$commit['Commit'] = $data;
		}
		return $commit;
	}

	/**
	 * undocumented function
	 *
	 * @param string $data
	 * @param string $options
	 * @return void
	 */
	function isUnique($data, $options = array()) {
		if (!empty($data['revision'])) {
			$this->recursive = -1;
			$project_id = $this->Project->id;
			if (!empty($this->data['Commit']['project_id'])) {
				$project_id = $this->data['Commit']['project_id'];
			}
			if ($id = $this->field('id', array('revision' => $data['revision'], 'project_id' => $project_id))) {
				if ($this->id && $this->id == $id) {
					return true;
				}
				return false;
			}
		}
		return true;
	}
}
?>