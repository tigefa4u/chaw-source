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
class RepoController extends AppController {

	var $name = 'Repo';
	var $uses = array('Commit');

	function fast_forward() {
		if ($this->Project->Repo->type != 'git') {
			$this->Session->setFlash(__('You cannot fork an svn project yet',true));
			$this->redirect($this->referer());
		}
		//$this->Project->Repo->logResponse = true;
		if ($this->Project->Repo->merge($this->Project->current['url'])) {
			$this->Session->setFlash(__('Fast Forward successfull!',true));
		} else {
			$this->Session->setFlash(__('Fast Forward failed. Time to merge manually?',true));
		}
		//CakeLog::write($this->Project->Repo->debug, LOG_DEBUG);
		//CakeLog::write($this->Project->Repo->response, LOG_DEBUG);
		$this->redirect($this->referer());
	}

	function merge($fork = null) {
		if ($this->Project->Repo->type != 'git') {
			$this->Session->setFlash(__('You cannot fork an svn project yet',true));
			$this->redirect($this->referer());
		}
		if (!$fork) {
			$this->Session->setFlash(__('Invalid Fork',true));
			$this->redirect($this->referer());
		}
		//$this->Project->Repo->logResponse = true;
		if ($this->Project->Repo->merge($this->Project->current['url'], $fork)) {
			$data = $this->Project->Repo->read(null, false);
			$this->Commit->create(array(
				'project_id' =>  $this->Project->id,
				'branch' => 'refs/heads/master'
			));
			$this->Commit->save($data);
			$this->Session->setFlash(__('Merge successfull!',true));
		} else {
			$this->Session->setFlash(__('Merge failed. Time to merge manually?',true));
		}
		//CakeLog::write($this->Project->Repo->debug, LOG_DEBUG);
		//CakeLog::write($this->Project->Repo->response, LOG_DEBUG);
		$this->redirect($this->referer());
	}

	function rebase() {
		if (!empty($this->params['isAdmin'])) {
			if ($this->Project->Repo->rebase()) {
				$this->Session->setFlash(__('You should have a nice clean working copy',true));
			} else {
				$this->Session->setFlash(__('Oops, rebuild failed try again',true));
			}
		}
		$this->redirect(array('controller' => 'source', 'action' => 'index'));
	}

	function fork_it() {
		if ($this->Project->Repo->type != 'git') {
			$this->Session->setFlash(__('You cannot fork an svn project',true));
			$this->redirect($this->referer());
		}

		if (!empty($this->params['form']['cancel'])) {
			$this->redirect(array('controller' => 'source', 'action' => 'index'));
		}

		if (!empty($this->data)) {
			$this->Project->create(array_merge(
				$this->Project->current,
				array(
					'user_id' => $this->Auth->user('id'),
					'fork' => $this->Auth->user('username'),
					'approved' => 1,
				)
			));
			if ($data = $this->Project->fork()) {
				$this->Session->setFlash(__('Fork was created',true));
				$this->redirect(array(
					'plugin' => null,
					'project' => $data['Project']['url'], 'fork' => $data['Project']['fork'],
					'controller' => 'source', 'action' => 'index',
				));
			} else {
				if (!empty($this->Project->data)) {
					$this->Session->setFlash(__('You already have a fork',true));
					$this->redirect(array(
						'fork' => $this->Project->data['Project']['fork'],
						'controller' => 'source', 'action' => 'index',
					));

				}
				$this->Session->setFlash(__('Project was NOT created',true));
			}
		}

		if (empty($this->data)) {
			$hasFork = $this->Project->find(array(
				'fork' => $this->Auth->user('username'),
				'url' => $this->Project->current['url']
			));
			if ($hasFork) {
				$this->Session->setFlash(__('You already have a fork',true));
				$this->redirect(array(
					'fork' => $this->Auth->user('username'),
					'controller' => 'source', 'action' => 'index',
				));
			}
		}
	}

	function download() {
		if ($this->RequestHandler->ext == 'tar') {
			$this->set(array(
				'project' => basename($this->Project->Repo->working),
				'working' => $this->Project->Repo->working
			));

			$this->render('download');
			return;
		}
		$this->redirect($this->referer());
	}

}
?>