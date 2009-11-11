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
 * @subpackage		chaw.controllers
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class CommitsController extends AppController {

	var $name = 'Commits';

	var $helpers = array('Time', 'Text');

	var $paginate = array('order' => 'Commit.commit_date DESC');

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

	function view($revision = null) {
		$branches = $this->Project->Repo->find('branches');
		$commit = $this->Commit->findByRevision($revision);
		$this->set(compact('commit', 'branches'));
	}

	function logs($commits = null) {
		$Source = ClassRegistry::init('Source');
		$this->paginate = array('order' => 'asc');
		$commits = $this->paginate($this->Project->Repo, array($commits));
		$this->set(compact('commits', 'args', 'current'));
	}

	function branch() {
		$branches = $this->Project->Repo->find('branches');
		$args = func_get_args();
		if ($this->Project->Repo->type == 'git') {
			if (empty($args)) {
				$this->Project->Repo->branch('master', true);
			}
		}

		$Source = ClassRegistry::init('Source');
		list($args, $path, $current) = $Source->initialize($this->Project->Repo, $args);

		$this->paginate['branch'] =  $current;
		$commits = $this->paginate($this->Project->Repo);

		$this->set(compact('commits', 'branches', 'args', 'current'));
	}

	function history() {
		$args = func_get_args();
		if ($this->Project->Repo->type == 'git') {
			if (empty($args)) {
				$this->Project->Repo->branch('master', true);
			} else {
				array_shift($args);
			}
		}

		$Source = ClassRegistry::init('Source');

		list($args, $path, $current) = $Source->initialize($this->Project->Repo, $args);
		$this->paginate = array_merge(array('path' => $path, 'branch' => $args[1]), $this->paginate);
		$commits = $this->paginate($this->Project->Repo);
		$this->set(compact('commits', 'args', 'current'));
	}

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
