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
class WikiController extends AppController {

	var $name = 'Wiki';

	var $helpers = array('Text');

	function index() {
		extract($this->__params());

		$canWrite = $canDelete = true;

		if (empty($this->params['isAdmin'])) {
			$canWrite = $this->Access->check($this, array('access' => 'w'));
			$canDelete = $this->Access->check($this, array('access' => 'd'));
		}

		if (!$slug) {
			$slug = 'home';
		}

		$this->pageTitle = 'Wiki/' . ltrim($path . '/' . $slug, '/');

		$wiki = $this->Wiki->find('all', array(
			'conditions' => array(
				'Wiki.path' => str_replace('//', '/', $path . '/' . $slug),
				'Wiki.project_id' => $this->Project->id,
				'Wiki.active' => 1
			),
			'order' => 'Wiki.created DESC'
		));

		if (!empty($this->data)) {

			if (!empty($this->params['form']['delete'])) {
				$this->params['action'] = 'delete';
				if ($canDelete !== true) {
					$this->Session->setFlash(__('You are not authorized to delete.', true));
				} else {
	 				$this->Wiki->delete($this->data['Wiki']['revision']);
				}
			} else {
				$page = $this->Wiki->findById($this->data['Wiki']['revision']);
			}

			if (!empty($this->params['form']['activate']) && !empty($page)) {
				if ($canWrite !== true) {
					$this->Session->setFlash(__('You are not authorized to activate.', true));
				} else if ($this->Wiki->activate($page)) {
					$this->Session->setFlash($page['User']['username'] . ' ' . $page['Wiki']['created'] .' is now active');
				}
			}
		}

		if (empty($page)) {
			$page = $this->Wiki->find(array(
				'Wiki.slug' => $slug,
				'Wiki.path' => $path,
				'Wiki.project_id' => $this->Project->id,
				'Wiki.active' => 1
			));
		}

		if (empty($wiki) && empty($page)) {
			$this->passedArgs[] = $slug;
			$this->redirect(array_merge(array('action' => 'add'), $this->passedArgs));
		}

		if ($this->RequestHandler->isRss() !== true) {
			$paths = array_flip($this->Wiki->find('list', array(
				'fields' => array('Wiki.path', 'Wiki.id'),
				'conditions' => array(
					'Wiki.path !=' => '/',
					'Wiki.project_id' => $this->Project->id,
					'Wiki.active' => 1
				)
			)));
			sort($paths);

			$recents = $this->Wiki->find('all', array(
				'fields' => array('Wiki.path', 'Wiki.slug'),
				'conditions' => array(
					'Wiki.project_id' => $this->Project->id,
					'Wiki.active' => 1
				),
				'limit' => 10,
				'order' => 'Wiki.id DESC'
			));
		}

		if(!empty($page) && $canWrite) {
			$this->Wiki->recursive = 0;
			$revisions = $this->Wiki->find('superList', array(
				'fields' => array('id', 'User.username', 'created'),
				'separator' => ' - ',
				'conditions' => array(
					'Wiki.slug' => $slug,
					'Wiki.path' => $path,
					'Wiki.project_id' => $this->Project->id,
					'User.username !=' => null
				),
				'order' => 'Wiki.created DESC'
			));
		}

		$this->set(compact('canWrite', 'canDelete', 'path', 'slug', 'wiki', 'page', 'paths', 'recents', 'revisions'));
		$this->render('index');
	}

	function add() {

		extract($this->__params());

		if ($slug == '1') {
			$slug = null;
			$this->pageTitle = 'Wiki/add/';
		}

		$this->pageTitle .= ltrim($path . '/' . $slug, '/');

		if (!empty($this->data)) {
			$this->Wiki->create(array(
				'project_id' => $this->Project->id,
				'last_changed_by' => $this->Auth->user('id')
			));
			if ($data = $this->Wiki->save($this->data)) {
				$this->Session->setFlash($data['Wiki']['slug'] . ' saved');
				$this->redirect(array('controller' => 'wiki', 'action' => 'index', $data['Wiki']['path'], $data['Wiki']['slug']));
			} else {
				$this->Session->setFlash($data['Wiki']['slug'] . ' NOT saved');
			}
		}

		if (empty($this->data) && $slug !== '1') {
			$this->data = $this->Wiki->find(array(
				'Wiki.slug' => $slug,
				'Wiki.path' => $path,
				'Wiki.project_id' => $this->Project->id,
				'Wiki.active' => 1
			));
			if (!empty($this->data)) {
				$this->data['Wiki']['active'] = 1;
			}
			$canEdit = !empty($this->params['isAdmin']) || $this->Auth->user('id') === $this->data['Wiki']['last_changed_by'];
			if (!empty($this->data['Wiki']['read_only']) && !$canEdit) {
				$this->redirect(array('controller' => 'wiki', 'action' => 'index', $path, $slug));
			}
			$this->data['Wiki']['slug'] = $slug;
			$this->data['Wiki']['path'] = $path;
		}

		$this->set(compact('path', 'slug'));
	}

	function edit() {
		$this->pageTitle = 'Wiki/edit/';
		$this->add();
		$this->render('add');
	}

	function __params() {
		$path = '/'; $slug = null;
		$slug = $this->Wiki->slug(array_pop($this->passedArgs));
		if(count($this->passedArgs) >= 1) {
			$path = '/'. join('/', $this->passedArgs);
		}
		return compact('slug', 'path');
	}
}
?>