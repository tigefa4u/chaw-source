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
class SourceController extends AppController {
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $name = 'Source';
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->mapActions(array(
			'branches' => 'read',
			'rebase' => 'update'
		));
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function index() {
		$args = func_get_args();
		if ($this->Project->Repo->type == 'git') {
			$this->Project->Repo->branch('master', true);
		} else {
			$this->Project->Repo->update();
		}
		
		list($args, $path, $current) = $this->Source->initialize($this->Project->Repo, $args);

		$data = $this->Source->read($path);
		
		$this->pageTitle = $current;
		if (!empty($args)) {
			$this->pageTitle = join('/', $args) . '/' . $current;
		}

		$this->set(compact('data', 'path', 'args', 'current'));
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function branches() {
		$args = func_get_args();
		if ($this->Project->Repo->type == 'svn') {
			array_unshift($args, 'branches');
		}
		list($args, $path, $current) = $this->Source->initialize($this->Project->Repo, $args);

		$data = $this->Source->read($path);

		$this->pageTitle = $current;
		if (!empty($args)) {
			$this->pageTitle = join('/', $args) . '/' . $current;
		}

		$branch = $this->Project->Repo->branch;
		$this->set(compact('data', 'path', 'args', 'current', 'branch'));

		$this->render('index');
	}

/**
 * undocumented function
 *
 * @return void
 *
 **/
	function delete($branch = null) {
		$this->autoRender = false;
		if (!empty($branch) && !empty($this->params['isAdmin'])) {
			$this->Source->initialize($this->Project->Repo, array($branch));
			if ($this->Project->Repo->delete()) {
				$this->Session->setFlash(sprintf(__('%s was deleted',true),$branch));
			} else {
				$this->Session->setFlash(__('Oops, delete failed try again',true));
			}
		}
		$this->redirect(array('action' => 'branches'));
	}
}
?>