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
			'rebuild' => 'update'
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
		}
		list($args, $path, $current) = $this->Source->initialize($this->Project->Repo, $args);


		$data = $this->Source->read($path);

		$this->pageTitle = 'Source';
		if ($path && $current) {
			$this->pageTitle = $path;
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

		$this->pageTitle = 'Source';
		if ($path && $current) {
			$this->pageTitle = $path;
		}

		$this->set(compact('data', 'path', 'args', 'current'));

		$this->render('index');
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function rebuild() {
		if (!empty($this->params['isAdmin'])) {
			if ($this->Source->rebuild()) {
				$this->Session->setFlash('You should have a nice clean working copy');
			} else {
				$this->Session->setFlash('Oops, rebuild failed try again');
			}
		}
		$this->redirect($this->referer());
	}
}
?>