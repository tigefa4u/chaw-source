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
			$this->Session->setFlash('You cannot fork an svn project yet');
			$this->redirect($this->referer());
		}
		$this->Project->Repo->logResponse = true;
		if ($this->Project->Repo->merge($this->Project->config['url'])) {
			$this->Session->setFlash('Fast Forward successfull!');
		} else {
			$this->Session->setFlash('Fast Forward failed. Time to merge manually?');
		}
		$this->log($this->Project->Repo->debug, LOG_DEBUG);
		$this->log($this->Project->Repo->response, LOG_DEBUG);
		$this->redirect($this->referer());
	}

	function merge($fork = null) {
		if ($this->Project->Repo->type != 'git') {
			$this->Session->setFlash('You cannot fork an svn project yet');
			$this->redirect($this->referer());
		}
		if (!$fork) {
			$this->Session->setFlash('Invalid Fork');
			$this->redirect($this->referer());
		}
		$this->Project->Repo->logResponse = true;
		if ($this->Project->Repo->merge($this->Project->config['url'], $fork)) {
			$data = $this->Project->Repo->read(null, false);
			$this->Commit->create(array(
				'project_id' =>  $this->Project->id,
				'branch' => 'refs/heads/master'
			));
			$this->Commit->save($data);
			$this->Session->setFlash('Merge successfull!');
		} else {
			$this->Session->setFlash('Merge failed. Time to merge manually?');
		}
		$this->log($this->Project->Repo->debug, LOG_DEBUG);
		$this->log($this->Project->Repo->response, LOG_DEBUG);
		$this->redirect($this->referer());
	}

	function fork_it() {
		if ($this->Project->Repo->type != 'git') {
			$this->Session->setFlash('You cannot fork an svn project');
			$this->redirect($this->referer());
		}

		if (!empty($this->params['form']['cancel'])) {
			$this->redirect(array('controller' => 'source'));
		}

		if (!empty($this->data)) {
			$this->Project->create(array_merge(
				$this->Project->config,
				array(
					'user_id' => $this->Auth->user('id'),
					'fork' => $this->Auth->user('username'),
					'approved' => 1,
				)
			));
			if ($data = $this->Project->fork()) {
				if (empty($data['Project']['approved'])) {
					$this->Session->setFlash('Project is awaiting approval');
				} else {
					$this->Session->setFlash('Project was created');
				}
				$this->redirect(array(
					'fork' => $data['Project']['fork'],
					'controller' => 'source', 'action' => 'index',
				));
			} else {
				if (!empty($this->Project->data)) {
					$this->Session->setFlash('You already have a fork');
					$this->redirect(array(
						'fork' => $this->Project->data['Project']['fork'],
						'controller' => 'source', 'action' => 'index',
					));

				}
				$this->Session->setFlash('Project was NOT created');
			}
		}

		if (empty($this->data)) {
			$hasFork = $this->Project->find(array(
				'fork' => $this->Auth->user('username'),
				'url' => $this->Project->config['url']
			));
			if ($hasFork) {
				$this->Session->setFlash('You already have a fork');
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