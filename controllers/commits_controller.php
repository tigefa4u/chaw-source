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
class CommitsController extends AppController {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Commits';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $helpers = array('Time', 'Text');

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $paginate = array('order' => 'Commit.commit_date DESC');

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function index() {
		$this->Commit->recursive = 0;
		$this->Commit->bindModel(array('hasOne' => array(
			'Timeline' => array(
				'className' => 'Timeline',
				'foreignKey' => 'foreign_key',
				'conditions' => array('Timeline.model = \'Commit\''),
		))), false);

		$conditions = array('Commit.project_id' => $this->Project->id);
		$this->set('commits', $this->paginate('Commit', $conditions));
	}

	/**
	 * undocumented function
	 *
	 * @param string $revision
	 * @return void
	 */
	function view($revision = null) {
		$branches = $this->Project->Repo->find('branches');
		$commit = $this->Commit->findByRevision($revision);
		$this->set(compact('commit', 'branches'));
	}

	/**
	 * undocumented function
	 *
	 * @param string $commits
	 * @return void
	 */
	function logs($commits = null) {
		$Source = ClassRegistry::init('Source');
		$this->paginate = array('order' => 'asc');
		$commits = $this->paginate($this->Project->Repo, array($commits));
		$this->set(compact('commits', 'args', 'current'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function branch() {
		$branches = $this->Project->Repo->find('branches');
		$args = func_get_args();
		$Source = ClassRegistry::init('Source');
		list($args, $path, $current) = $Source->initialize($this->Project->Repo, $args);
		$this->paginate['branch'] =  $current;
		$commits = $this->paginate($this->Project->Repo);
		$this->set(compact('commits', 'branches', 'args', 'current'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function history() {
		$args = func_get_args();
		$Source = ClassRegistry::init('Source');
		list($args, $path, $current) = $Source->initialize($this->Project->Repo, $args);
		$this->paginate = array_merge(array('path' => $path, 'branch' => $args[1]), $this->paginate);
		$commits = $this->paginate($this->Project->Repo);
		$this->set(compact('commits', 'args', 'current'));
	}

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function remove($id = null) {
		if (!$id || empty($this->params['isAdmin'])) {
			$this->redirect($this->referer());
		}

		$this->Commit->bindModel(array('hasOne' => array(
			'Timeline' => array(
				'className' => 'Timeline',
				'foreignKey' => 'foreign_key',
				'conditions' => array('Timeline.model = \'Commit\''),
				'dependent' => true
		))), false);

		if ($this->Commit->del($id)) {
			$this->Session->setFlash(__('The commit was deleted',true));
		} else {
			$this->Session->setFlash(__('The commit was NOT deleted',true));
			if ($timeline = $this->Commit->Timeline->find('id', array('Timeline.foreign_key' => $id, 'Timeline.model = \'Commit\''))) {
				if ($this->Commit->Timeline->del($timeline)) {
					$this->Session->setFlash(__('The commit was removed from timeline',true));
				}
			}
		}
		$this->redirect(array('action' => 'index'));
	}
}
?>