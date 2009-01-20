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
class ProjectsController extends AppController {

	var $name = 'Projects';

	var $paginate = array(
		'order' => 'Project.users_count DESC, Project.created ASC'
	);

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->mapActions(array('fork' => 'create'));
		$this->Auth->allow('index');
		$this->Access->allow('index', 'start');
	}

	function index() {
		Router::connectNamed(array('type', 'page'));

		$projects = $this->Access->user('Permission');

		if (empty($projects) || !empty($this->passedArgs['type'])) {

			$this->Project->recursive = 0;

			$this->paginate['conditions'] = array(
				'Project.private' => 0, 'Project.active' => 1, 'Project.approved' => 1
			);

			if ($this->params['isAdmin'] === true) {
				$this->paginate['conditions'] = array();
				$this->paginate['order'] = 'Project.id ASC';
			}

			if(empty($this->passedArgs['type'])) {
				$this->passedArgs['type'] = 'public';
			}

			if ($this->passedArgs['type'] == 'forks') {
				$this->paginate['conditions']['Project.fork !='] = null;
			} else if ($this->passedArgs['type'] == 'public') {
				$this->paginate['conditions']['Project.fork ='] = null;
			}

		} else {
			$this->passedArgs['type'] = null;
			$this->paginate['conditions'] = array('Project.id' => array_keys($projects));
		}

		$projects  = $this->paginate();
		$this->set('projects', $projects);

		$this->set('rssFeed', array('controller' => 'projects'));
	}

	function forks() {
		$this->paginate['conditions'] = array(
			'Project.fork !=' => null, 'Project.project_id' =>  $this->Project->id
		);

		$this->set('projects', $this->paginate());
		$this->set('rssFeed', array('controller' => 'projects', 'action' => 'forks'));

		$this->render('index');
	}

	function view($url  = null) {
		$project = array('Project' => $this->Project->config);
		if (empty($this->params['project']) && $url == null && $project['id'] != 1) {
			$project = $this->Project->findByUrl($url);
		}

		$this->set('project', $project);
	}

	function start($type = null) {
		$this->pageTitle = 'Projects/Start';
		if ($type || !empty($this->data)) {
			$this->add();
			return;
		}
	}

	function add() {

		if (!empty($this->data)) {
			$this->Project->create(array(
				'user_id' => $this->Auth->user('id'),
				'username' => $this->Auth->user('username'),
				'approved' => $this->params['isAdmin']
			));
			if ($data = $this->Project->save($this->data)) {
				if (empty($data['Project']['approved'])) {
					$this->Session->setFlash('Project is awaiting approval');
					$this->redirect(array(
						'project' => $data['Project']['url'],
						'controller' => 'projects', 'action' => 'view'
					));
				} else {
					$this->Session->setFlash('Project was created');
					$this->redirect(array(
						'project' => $data['Project']['url'],
						'controller' => 'timeline', 'action' => 'index'
					));
				}
			} else {
				$this->Session->setFlash('Project was NOT created');
			}
		}

		if (empty($this->data)) {
			$this->data = array_merge((array)$this->data, array('Project' => $this->Project->config));
			if (!empty($this->data['Project']['id'])) {
				unset($this->data['Project']['id'], $this->data['Project']['name'], $this->data['Project']['description']);
			}
		}

		if (!empty($this->passedArgs[0])) {
			$this->pageTitle = Inflector::humanize($this->passedArgs[0]) . '/Project/Setup';
			$this->data['Project']['private'] = ($this->passedArgs[0] == 'public') ? 0 : 1;
		}

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);

		$this->render('add');
	}

	function edit() {
		if ($this->params['isAdmin'] === false) {
			$this->redirect($this->referer());
		}

		$this->pageTitle = 'Update Project';

		if (!empty($this->data)) {
			$this->data['Project']['id'] = $this->Project->id;
			if ($data = $this->Project->save($this->data)) {
				$this->Session->setFlash('Project was updated');
			} else {
				$this->Session->setFlash('Project was NOT updated');
			}
		}

		$this->data = $this->Project->read();

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);

		$this->render('edit');
	}

	function admin_index() {
		if ($this->Project->id != 1 || $this->params['isAdmin'] === false) {
			$this->redirect($this->referer());
		}

		if ($this->params['isAdmin'] === true) {
			$this->paginate['conditions'] = array();
			$this->paginate['order'] = 'Project.id ASC';
		}

		if(empty($this->passedArgs['type'])) {
			$this->passedArgs['type'] = 'public';
		}

		if ($this->passedArgs['type'] == 'forks') {
			$this->paginate['conditions']['Project.fork !='] = null;
		} else if ($this->passedArgs['type'] == 'public') {
			$this->paginate['conditions']['Project.fork ='] = null;
		}

		if ($this->passedArgs['type'] == 'pending') {
			$this->paginate['conditions']['Project.approved'] = 0;
		}

		$this->Project->recursive = 0;
		$this->set('projects', $this->paginate());
	}

	function admin_add() {

		$this->pageTitle = 'Project Setup';

		if ($this->Project->id !== '1' || $this->params['isAdmin'] === false) {
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
			$this->Project->create(array(
				'user_id' => $this->Auth->user('id'),
				'username' => $this->Auth->user('username'),
				'approved' => 1
			));
			if ($data = $this->Project->save($this->data)) {
				$this->Session->setFlash('Project was created');
				$this->redirect(array(
					'admin' => false, 'project' => $data['Project']['url'],
					'controller' => 'timeline', 'action' => 'index'
				));
			} else {
				$this->Session->setFlash('Project was NOT created');
			}
		}

		if (empty($this->data)) {
			$this->data = array_merge((array)$this->data, array('Project' => $this->Project->config));
			if (!empty($this->data['Project']['id'])) {
				unset($this->data['Project']['id'], $this->data['Project']['name'], $this->data['Project']['description']);
			}
		}

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);
	}

	function admin_edit($id = null) {
		if (!$id) {
			$this->Session->setFlash('The project was invalid');
			$this->redirect(array('action' => 'index'));
		}

		$this->pageTitle = 'Project Admin';

		if ($this->Project->id !== '1' || $this->params['isAdmin'] === false) {
			$this->redirect($this->referer());
		}

		$this->Project->id = $id;

		if (!empty($this->data)) {
			if ($data = $this->Project->save($this->data)) {
				$this->Session->setFlash('Project was updated');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Project was NOT updated');
			}
		}

		$this->data = $this->Project->read();

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);
	}

	function admin_approve($project = null) {
		$this->_toggle($project, array(
			'field' => 'approved', 'value' => 1, 'action' => 'approved'
		));
	}

	function admin_reject($project = null) {
		$this->_toggle($project, array(
			'field' => 'approved', 'value' => 0, 'action' => 'rejected'
		));

	}

	function admin_activate($project = null) {
		$this->_toggle($project, array(
			'field' => 'active', 'value' => 1, 'action' => 'activated'
		));
	}

	function admin_deactivate($project = null) {
		$this->_toggle($project, array(
			'field' => 'active', 'value' => 0, 'action' => 'deactivated'
		));
	}

	function _toggle($project, $options = array()) {
		$options = array_merge(array('field' => null, 'value' => null, 'action' => null), $options);

		$isValid = $project && !empty($options['field']) && !empty($options['action']) &&
			$this->Project->id == 1 && !empty($this->params['isAdmin']);

		if ($isValid) {
			if ($this->Project->initialize(compact('project'))) {
				$this->Project->set($this->Project->config);
				if ($this->Project->save(array($options['field'] => $options['value']))) {
					$this->Session->setFlash('The project was ' . $options['action']);
				} else {
					$this->Session->setFlash('The project was NOT ' . $options['action']);
				}
			} else {
				$this->Session->setFlash('The project was invalid');
			}
		} else {
			$this->Session->setFlash('The project was invalid');
		}
		$this->redirect(array('project' => false, 'fork' => false, 'action' => 'index'));
	}
}
?>