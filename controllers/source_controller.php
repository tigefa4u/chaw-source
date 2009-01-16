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

	var $name = 'Source';

	function index() {
		$args = func_get_args();
		$path = join(DS, $args);

		if (empty($path)) {
			$this->Project->Repo->update();
		}

		if ($this->Project->Repo->type == 'git') {
			array_unshift($args, 'branches', 'master');
			$this->Project->Repo->branch('master', true);
		}

		$current = null;
		if (count($args) > 0) {
			$current = array_pop($args);
		}

		$data = $this->Source->read($this->Project->Repo, $path);

		if ($path && $current) {
			$this->pageTitle = $path;
		} else {
			$this->pageTitle = 'Source';
		}

		$this->set(compact('data', 'path', 'args', 'current'));
	}

	function branches() {
		$args = func_get_args();
		$path = join(DS, $args);

		$branch = $branchPath = null;
		if ($this->Project->Repo->type == 'git') {
			$branch = array_shift($args);
			$branchPath = join(DS, $args);
		}

		if ($branch) {
			array_unshift($args, 'branches', $branch);
		} else {
			array_unshift($args, 'branches');
		}

		if ($this->Project->Repo->type == 'svn') {
			$path = join(DS, $args);
			if (empty($path)) {
				$this->Project->Repo->update();
			}
			$data = $this->Source->read($this->Project->Repo, $path);
		}

		$current = null;
		if (count($args) > 0) {
			$current = array_pop($args);
 		}

		if ($this->Project->Repo->type == 'git') {
			if ($branch === null) {
				$branches = $this->Project->branches();
				foreach ($branches as $branch) {
					$this->Project->Repo->branch($branch['Branch']['name']);
				}
			} else {
				if (empty($branchPath)) {
					$this->Project->Repo->branch($branch, true);
					$this->Project->Repo->before(array("cd {$this->Project->Repo->working}"));
					$this->Project->Repo->run('pull');
				}
				$this->Project->Repo->branch($branch, true);
			}

			$data = $this->Source->read($this->Project->Repo, $branchPath);
		}

		if ($path && $current) {
			$this->pageTitle = $path;
		} else {
			$this->pageTitle = 'Source';
		}

		$this->set(compact('data', 'path', 'args', 'current'));

		$this->render('index');
	}
}
?>