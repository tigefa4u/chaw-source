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
class SourceController extends AppController {

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	var $name = 'Source';

	/**
	 * undocumented function
	 *
	 * @return void
	 *
	 */
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
	 */
	function index() {
		$args = func_get_args();
		if ($this->Project->Repo->type == 'git') {
			$this->Project->Repo->branch('master', true);
		} else {
			$this->Project->Repo->update();
		}

		list($args, $path, $current) = $this->Source->initialize($this->Project->Repo, $args);

		$title = $current;

		if (!empty($args)) {
			$title = join('/', $args) . '/' . $current;
		}
		$this->set('title_for_layout', $title);
		
		$data = $this->Source->read($path);

		$this->set(compact('data', 'path', 'args', 'current'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 *
	 */
	function branches() {
		$args = func_get_args();
		if ($this->Project->Repo->type == 'svn') {
			array_unshift($args, 'branches');
		}
		list($args, $path, $current) = $this->Source->initialize($this->Project->Repo, $args);

		$data = $this->Source->read($path);

		$this->set('title_for_layout', $current);
		if (!empty($args)) {
			$this->set('title_for_layout', join('/', $args) . '/' . $current);
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
	 */
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