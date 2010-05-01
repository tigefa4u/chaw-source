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
class ProjectsController extends AppController {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Projects';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $paginate = array(
		'order' => 'Project.users_count DESC, Project.created ASC'
	);

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->mapActions(array('fork' => 'create'));
		$this->Auth->allow('index');
		$this->Access->allow('index', 'start');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function index() {
		$this->set('disableNav', true);

		Router::connectNamed(array('type', 'page'));

		if (empty($this->passedArgs['type'])) {
			$this->passedArgs['type'] = 'public';
			$projects = $this->Project->User->groups($this->Auth->user('id'));
			if (!empty($projects)) {
				$this->Session->write('Auth.User.Permission', $projects);
				$this->passedArgs['type'] = null;
				$this->paginate['conditions'] = array('Project.id' => array_keys($projects));
				$this->paginate['order'] = 'Project.private DESC, Project.id ASC';
			}
		}

		if (!empty($this->passedArgs['type'])) {

			$this->paginate['conditions'] = array(
				'Project.private' => 0, 'Project.active' => 1, 'Project.approved' => 1
			);

			if ($this->params['isAdmin'] === true && $this->Project->id == 1) {
				unset($this->paginate['conditions']['Project.private']);
				$this->paginate['order'] = 'Project.private ASC, Project.id ASC';
			}

			if(empty($this->passedArgs['type'])) {
				$this->passedArgs['type'] = 'public';
			}

			if ($this->passedArgs['type'] == 'forks') {
				$this->paginate['conditions']['Project.fork !='] = null;
			} else if ($this->passedArgs['type'] == 'public') {
				$this->paginate['conditions']['Project.fork ='] = null;
			}

		}

		$this->Project->recursive = 0;
		$projects  = $this->paginate();
		$this->set('projects', $projects);

		$this->set('rssFeed', array('controller' => 'projects'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function forks() {
		$this->set('disableNav', true);
		
		$this->paginate['conditions'] = array(
			'Project.fork !=' => null, 'Project.project_id' =>  $this->Project->id
		);

		$this->set('projects', $this->paginate());
		$this->set('rssFeed', array('controller' => 'projects', 'action' => 'forks'));

		$this->render('index');
	}

	/**
	 * undocumented function
	 *
	 * @param string $url
	 * @return void
	 */
	function view($url  = null) {
		$project = array('Project' => $this->Project->current);
		if (empty($this->params['project']) && $url == null && $project['id'] != 1) {
			$project = $this->Project->findByUrl($url);
		}

		$this->set('project', $project);
	}

	/**
	 * undocumented function
	 *
	 * @param string $type
	 * @return void
	 */
	function start($type = null) {
		$this->set('disableNav', true);
		
		$this->set('title_for_layout', 'Projects/Start');
		if ($type || !empty($this->data)) {
			$this->add();
			return;
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function add() {
		$this->set('disableNav', true);
		
		if (!empty($this->data)) {
			$this->Project->create(array(
				'user_id' => $this->Auth->user('id'),
				'username' => $this->Auth->user('username'),
				'approved' => $this->params['isAdmin']
			));
			if ($data = $this->Project->save($this->data)) {
				if (empty($data['Project']['approved'])) {
					$this->Session->setFlash(__('Project is awaiting approval',true));
					$this->redirect(array(
						'project' => $data['Project']['url'],
						'controller' => 'projects', 'action' => 'view'
					));
				} else {
					$this->Session->setFlash(__('Project was created',true));
					$this->redirect(array(
						'project' => $data['Project']['url'],
						'controller' => 'timeline', 'action' => 'index'
					));
				}
			} else {
				$this->Session->setFlash(__('Project was NOT created',true));
			}
		}

		if (empty($this->data)) {
			$this->data = array('Project' => $this->Project->current);
			$this->data['config']['ticket'] = $this->Project->current['config']['ticket'];
			if (!empty($this->data['Project']['id'])) {
				unset($this->data['Project']['id'], $this->data['Project']['name'], $this->data['Project']['description']);
			}
		}

		if (!empty($this->passedArgs[0])) {
			$this->set('title_for_layout', Inflector::humanize($this->passedArgs[0]) . '/Project/Setup');
			$this->data['Project']['private'] = ($this->passedArgs[0] == 'public') ? 0 : 1;
		}

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);

		$this->render('add');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function edit() {
		if ($this->params['isAdmin'] === false) {
			$this->redirect($this->referer());
		}

		$this->set('title_for_layout', 'Update Project');

		if (!empty($this->data)) {
			$this->data['Project']['id'] = $this->Project->id;
			$this->data['Project'] = array_merge($this->Project->current, $this->data['Project']);
			if ($data = $this->Project->save($this->data)) {
				$this->Session->setFlash(__('Project was updated',true));
			} else {
				$this->Session->setFlash(__('Project was NOT updated',true));
			}
			$this->redirect();
		}

		$this->data = array('Project' => $this->Project->current);
		$this->data['config']['ticket'] = $this->Project->current['config']['ticket'];

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);

		$this->render('edit');
	}

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function remove($id) {
		$project = $this->Project->findById($id);
		if (empty($project)) {
			$this->Session->setFlash(__("Invalid Project", true));
		} else {
			if ($this->Project->Permission->deleteAll(array('Permission.project_id' => $id, 'Permission.user_id' => $this->Auth->user('id')))) {
				$this->Session->setFlash(sprintf(__("%s was removed ", true), $project['Project']['name']));
			}
			$this->Session->write('Auth.User.Permission', $this->Project->User->groups($this->Auth->user('id')));
		}
		$this->redirect($this->referer());
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function delete() {
		if (!empty($this->params['form']['cancel'])) {
			$this->redirect(array('controller' => 'source'));
		}
		if (!empty($this->data['Project']['id'])) {

			if ($this->data['Project']['id'] != 1) {
				$project = $this->Project->findById($this->data['Project']['id']);
				if (empty($project)) {
					$this->Session->setFlash(__("Invalid Project", true));
					$this->redirect(array('controller' => 'source'));
				}
				$this->Project->set($project);
				if ($this->Project->initialize() && $this->Project->current['id'] != 1) {
					if ($this->Project->delete($this->data['Project']['id'])) {
						$this->Project->Permission->deleteAll(array('Permission.project_id' => $this->data['Project']['id']));
						$this->Session->setFlash(sprintf(__("%s was deleted ", true), $project['Project']['name']));
					} else {
						$this->Session->setFlash(sprintf(__("%s was NOT deleted ", true), $project['Project']['name']));
					}
				} else {
					$this->Session->setFlash(sprintf(__("%s could not be found", true), $project['Project']['name']));
				}
			}

			$this->redirect(array(
				'plugin'=> false, 'project' => false, 'fork' => false,
				'controller' => 'projects', 'action' => 'index'
			));
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
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

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function admin_add() {

		$this->set('title_for_layout', 'Project Setup');

		if ($this->Project->id !== '1' && $this->params['isAdmin'] !== true) {
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
			$this->Project->create(array(
				'user_id' => $this->Auth->user('id'),
				'username' => $this->Auth->user('username'),
				'approved' => 1
			));
			if ($data = $this->Project->save($this->data)) {
				$this->Session->setFlash(__('Project was created',true));
				$this->redirect(array(
					'admin' => false, 'project' => $data['Project']['url'],
					'controller' => 'timeline', 'action' => 'index'
				));
			} else {
				$this->Session->setFlash(__('Project was NOT created',true));
			}
		}

		if (empty($this->data)) {
			$this->data = array('Project' => $this->Project->current);
			$this->data['config']['ticket'] = $this->Project->current['config']['ticket'];
			if (!empty($this->data['Project']['id'])) {
				unset($this->data['Project']['id'], $this->data['Project']['name'], $this->data['Project']['description']);
			}
		}

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);
	}

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function admin_edit($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('The project was invalid',true));
			$this->redirect(array('action' => 'index'));
		}

		$this->set("title_for_layout", __('Project Admin',true));

		if ($this->Project->id !== '1' || $this->params['isAdmin'] === false) {
			$this->redirect($this->referer());
		}

		$this->Project->id = $id;
		$this->Project->Repo->logResponse = true;
		if (!empty($this->data)) {
			if ($data = $this->Project->save($this->data, false)) {
				$this->Session->setFlash(__('Project was updated',true));
			//	$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Project was NOT updated',true));
			}
		}

		$this->data = $this->Project->read();
		$this->data['config']['ticket'] = $this->data['Project']['config']['ticket'];

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);
	}

	/**
	 * undocumented function
	 *
	 * @param string $project
	 * @return void
	 */
	function admin_approve($project = null) {
		$this->_toggle($project, array(
			'field' => 'approved', 'value' => 1, 'action' => 'approved'
		));
	}

	/**
	 * undocumented function
	 *
	 * @param string $project
	 * @return void
	 */
	function admin_reject($project = null) {
		$this->_toggle($project, array(
			'field' => 'approved', 'value' => 0, 'action' => 'rejected'
		));

	}

	/**
	 * undocumented function
	 *
	 * @param string $project
	 * @return void
	 */
	function admin_activate($project = null) {
		$this->_toggle($project, array(
			'field' => 'active', 'value' => 1, 'action' => 'activated'
		));
	}

	/**
	 * undocumented function
	 *
	 * @param string $project
	 * @return void
	 */
	function admin_deactivate($project = null) {
		$this->_toggle($project, array(
			'field' => 'active', 'value' => 0, 'action' => 'deactivated'
		));
	}

	/**
	 * undocumented function
	 *
	 * @param string $project
	 * @param string $options
	 * @return void
	 */
	function _toggle($project, $options = array()) {
		$options = array_merge(array('field' => null, 'value' => null, 'action' => null), $options);

		$isValid = $project && !empty($options['field']) && !empty($options['action']) &&
			$this->Project->id == 1 && !empty($this->params['isAdmin']);

		if ($isValid) {
			if ($this->Project->initialize(compact('project'))) {
				$this->Project->set($this->Project->current);
				if ($this->Project->save(array($options['field'] => $options['value']))) {
					$this->Session->setFlash(sprintf(__('The project was %s',true),$options['action']));

					if ($options['field'] == 'approved') {
						$Email = $this->_loadEmail();
						$this->Project->User->id = $this->Project->current['user_id'];
						$Email->to = $this->Project->User->field('email');

						if ($options['value'] == 1) {
							$Email->subject = 'Approved';
							$content[] = "{$this->Project->current['name']} was approved.\n";
							$content[] = Router::url(array(
								'admin' => false, 'project' => $this->Project->current['url'],
								'controller' => 'source', 'action' => 'index',
							), true);
						} else {
							$Email->subject = 'Sorry';
							$content[] = "Sorry, {$this->Project->current['name']} is not approved at this time.\n";
						}

						$Email->lineLength = 120;
						$Email->send($content);
					}

				} else {
					$this->Session->setFlash(sprintf(__('The project was NOT %s',true),$options['action']));
				}
			} else {
				$this->Session->setFlash(__('The project was invalid',true));
			}
		} else {
			$this->Session->setFlash(__('The project was invalid',true));
		}
		$this->redirect(array('project' => false, 'fork' => false, 'action' => 'index'));
	}

	/**
	 * undocumented
	 *
	 */
	function &_loadEmail() {
		App::import('Component', 'Email');
		$Email = new EmailComponent();
		$Email->initialize($this);
		$from = $this->Project->from();
		$Email->from = 'Chaw ' . $from;
		$Email->replyTo = 'Chaw ' . $from;
		$Email->return = $from;
		return $Email;
	}
}
?>