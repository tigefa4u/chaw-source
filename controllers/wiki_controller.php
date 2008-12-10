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
			)
		));

		$page = $this->Wiki->find(array(
			'Wiki.slug' => $slug,
			'Wiki.path' => $path,
			'Wiki.project_id' => $this->Project->id,
			'Wiki.active' => 1
		));

		if (empty($wiki) && empty($page)) {
			$this->passedArgs[] = $slug;
			$this->redirect(array_merge(array('action' => 'add'), $this->passedArgs));
		}

		$paths = array_flip($this->Wiki->find('list', array(
			'fields' => array('Wiki.path', 'Wiki.id'),
			'conditions' => array(
				'Wiki.project_id' => $this->Project->id,
				'Wiki.active' => 1
			)
		)));
		sort($paths);
		$this->set(compact('path', 'slug', 'wiki', 'page', 'paths'));
		$this->render('view');
	}

	function add() {

		extract($this->__params());

		if ($slug == '1') {
			$slug = null;
			$this->pageTitle = 'Create a new page';
		} else {
			$this->pageTitle = Inflector::humanize($slug);
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
	}

	function edit() {
		$this->add();
		$this->render('add');
	}

	function __params() {
		$path = '/'; $slug = null;
		$slug = Inflector::slug(array_pop($this->passedArgs));
		if(count($this->passedArgs) >= 1) {
			$path = '/'. join('/', $this->passedArgs);
		}
		return compact('slug', 'path');
	}
}
?>