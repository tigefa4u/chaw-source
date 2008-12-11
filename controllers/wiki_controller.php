<?php
/**
 * Short description
 *
 * Long description
 *
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Licensed under The MIT License
 * Redistributions of files must retain the copyright notice.
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.controllers
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class WikiController extends AppController {

	var $name = 'Wiki';

	var $helpers = array('Text');

	function index() {
		extract($this->__params());

		if (!$slug) {
			$slug = 'home';
		}

		if ($title = substr($path, 1)) {
			$this->pageTitle = Inflector::humanize($title) . '/';
		}

		if ($slug) {
			$this->pageTitle .=  Inflector::humanize($slug);
		}

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
 				$this->Wiki->delete($this->data['Wiki']['revision']);
			} else {
				$page = $this->Wiki->findById($this->data['Wiki']['revision']);
			}

			if (!empty($this->params['form']['activate']) && !empty($page)) {
 				if ($this->Wiki->activate($page)) {
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
					'Wiki.project_id' => $this->Project->id,
					'Wiki.active' => 1
				)
			)));
			sort($paths);
		}

		if(!empty($page) && !empty($this->params['isAdmin'])) {
			$this->Wiki->recursive = 0;
			$revisions = $this->Wiki->find('superList', array(
				'fields' => array('id', 'User.username', 'created'),
				'separator' => ' - ',
				'conditions' => array(
					'Wiki.slug' => $slug,
					'Wiki.path' => $path,
					'Wiki.project_id' => $this->Project->id,
				),
				'order' => 'Wiki.created DESC'
			));
		}

		$this->set(compact('path', 'slug', 'wiki', 'page', 'paths', 'revisions'));
	}

	function add() {

		extract($this->__params());

		if ($slug == '1') {
			$slug = null;
			$this->pageTitle = 'Create a new page';
		}

		if ($heading = Inflector::humanize(substr($path, 1))) {
			$this->pageTitle = $heading . '/';
		}

		if ($slug) {
			$this->pageTitle .=  Inflector::humanize($slug);
		}

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
				$this->data['Wiki']['update'] = 1;
			}
			$this->data['Wiki']['slug'] = $slug;
			$this->data['Wiki']['path'] = $path;
		}

		$this->set(compact('path', 'slug', 'heading'));
	}

	function edit() {
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