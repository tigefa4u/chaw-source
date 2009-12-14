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
class WikiController extends AppController {

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
	var $helpers = array('Text');

	/**
	 * undocumented function
	 *
	 * @return void
	 */
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

		$fullpath = str_replace('//', '/', $path . '/' . $slug);

		$this->pageTitle = 'Wiki' . $fullpath;

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
		if (empty($page) || $this->RequestHandler->isRss() == true) {
			$wiki = $this->Wiki->find('all', array(
				'conditions' => array(
					'Wiki.path' => $fullpath,
					'Wiki.project_id' => $this->Project->id,
					'Wiki.active' => 1
				),
				'order' => 'Wiki.created DESC'
			));
		}

		if (empty($wiki) && empty($page)) {
			$this->passedArgs[] = $slug;
			$this->redirect(array('action' => 'add', $fullpath));
		}

		if ($this->RequestHandler->isRss() !== true) {

			if (!empty($page)) {
				$subNav = $this->Wiki->find('all', array(
					'fields' => array('Wiki.path', 'Wiki.slug'),
					'conditions' => array(
						'Wiki.path' => $fullpath,
						'Wiki.project_id' => $this->Project->id,
						'Wiki.active' => 1
					),
					'order' => 'Wiki.created DESC'
				));
			}

			$wikiNav = array_flip($this->Wiki->find('list', array(
				'fields' => array('Wiki.path', 'Wiki.id'),
				'conditions' => array(
					'Wiki.path !=' => '/',
					'Wiki.project_id' => $this->Project->id,
					'Wiki.active' => 1
				)
			)));
			sort($wikiNav);

			$recentEntries = $this->Wiki->find('all', array(
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
				'fields' => array('Wiki.id', 'User.username', 'Wiki.created'),
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

		$this->set(compact(
			'canWrite', 'canDelete', 'path', 'slug',
			'wiki', 'page', 'revisions',
			'subNav', 'wikiNav', 'recentEntries'
		));
		$this->render('index');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function add() {

		extract($this->__params());

		$fullpath = str_replace('//', '/', $path . '/' . $slug);

		$this->pageTitle = 'Wiki/add' . $fullpath;

		if ($slug === 'new-page') {
			$slug = null;
		}

		if (!empty($this->data)) {
			$this->Wiki->create(array(
				'project_id' => $this->Project->id,
				'last_changed_by' => $this->Auth->user('id'),
			));
			if ($data = $this->Wiki->save($this->data)) {
				$this->Session->setFlash(sprintf(__('%s saved',true),$data['Wiki']['slug']));
				$this->redirect(array('controller' => 'wiki', 'action' => 'index', $data['Wiki']['path'], $data['Wiki']['slug']));
			} else {
				$this->Session->setFlash(sprintf(__('%s NOT saved',true),$data['Wiki']['slug']));
			}
		}

		if (empty($this->data) && $slug !== '1') {
			$this->data = $this->Wiki->find('first', array(
				'conditions' => array(
					'Wiki.slug' => $slug,
					'Wiki.path' => $path,
					'Wiki.project_id' => $this->Project->id,
				), 'order' => 'Wiki.id DESC', 'limit' => 1
			));

			if (empty($this->data['Wiki']['active'])) {
				$this->data['Wiki']['active'] = 1;
			}
			$canEdit = !empty($this->params['isAdmin']) || !empty($this->data['Wiki']['last_changed_by']) && $this->Auth->user('id') === $this->data['Wiki']['last_changed_by'];
			if (!empty($this->data['Wiki']['read_only']) && !$canEdit) {
				$this->redirect(array('controller' => 'wiki', 'action' => 'index', $path, $slug));
			}
			$this->data['Wiki']['slug'] = $slug;
			$this->data['Wiki']['path'] = $path;
		}

		$this->set(compact('path', 'slug'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function edit() {
		$this->pageTitle = 'Wiki/edit/';
		$this->add();
		$this->render('add');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
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