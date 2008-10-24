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

	function index() {

		$slug = $this->__slug();

		if ($slug === null) {
			$slug = 'home';
		}

		$this->pageTitle = Inflector::humanize($slug);

		$wiki = $this->Wiki->find(array(
			'Wiki.slug' => $slug,
			'Wiki.project_id' => $this->Project->id,
			'Wiki.active' => 1
		));

		if (!empty($wiki)) {
			$this->set('wiki', $wiki);
			$this->render('view');
		} else {
			$this->data['Wiki']['slug'] = $slug;
			$this->render('add');
		}
	}

	function add() {

		$slug = $this->__slug();

		$this->pageTitle = Inflector::humanize($slug);

		if (!empty($this->data)) {
			$this->Wiki->create(array(
				'project_id' => $this->Project->id,
				'last_changed_by' => $this->Auth->user('id')
			));
			if ($data = $this->Wiki->save($this->data)) {
				$this->Session->setFlash($data['Wiki']['slug'] . ' saved');
				$this->redirect(array('controller' => 'wiki', 'action' => 'index', $data['Wiki']['slug']));
			} else {
				$this->Session->setFlash($data['Wiki']['slug'] . ' NOT saved');
			}
		}

		if (empty($this->data)) {

			$this->data = $this->Wiki->find(array(
				'Wiki.slug' => $slug,
				'Wiki.project_id' => $this->Project->id,
				'Wiki.active' => 1
			));
		}
	}

	function __slug() {
		$slug = null;
		if(count($this->passedArgs) >= 1) {
			$slug = Inflector::slug(join('/', $this->passedArgs));
		}

		return $slug;
	}
}
?>