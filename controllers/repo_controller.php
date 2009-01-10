<?php
class RepoController extends AppController {

	var $name = 'Repo';
	var $uses = array();

	function fast_forward() {
		if ($this->Project->Repo->type != 'git') {
			$this->Session->setFlash('You cannot fork an svn project yet');
			$this->redirect($this->referer());
		}
		if ($this->Project->Repo->merge($this->Project->config['url'])) {
			$this->Session->setFlash('Fast Forward successfull!');
		} else {
			$this->Session->setFlash('Fast Forward failed!');
		}
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

		if ($this->Project->Repo->merge($this->Project->config['url'], $fork)) {
			$this->Session->setFlash('Fast Forward successfull!');
		} else {
			$this->Session->setFlash('Fast Forward failed!');
		}
		$this->redirect($this->referer());
	}

	function fork_it() {
		if ($this->Project->Repo->type != 'git') {
			$this->Session->setFlash('You cannot fork an svn project');
			$this->redirect($this->referer());
		}

		if (!empty($this->params['form']['cancel'])) {
			$this->redirect(array('controller' => 'browser'));
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
					'controller' => 'browser', 'action' => 'index',
				));
			} else {
				$this->Session->setFlash('Project was NOT created');
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