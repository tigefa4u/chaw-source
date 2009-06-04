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
 * @subpackage		chaw.vendors.shells
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class PostReceiveShell extends Shell {

	var $uses = array( 'Project', 'Commit');

	function _welcome() {}

	function main() {
		return $this->commit();
	}

	function commit() {

		$project = str_replace('.git', '', trim(@$this->args[0], "'"));
		$refname = @$this->args[1];
		$oldrev = @$this->args[2];
		$newrev = @$this->args[3];

		$this->args[] = 'post-receive';
 		//$this->log($this->args, LOG_INFO);

		$fork = (!empty($this->params['fork']) && $this->params['fork'] != 1) ? $this->params['fork'] : null;

		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->current['url'] !== $project) {
			$this->err('Invalid project');
			return 1;
		}

		$user = $this->Project->User->field('id', array('username' => $_SERVER['PHP_CHAWUSER']));

		if (!isset($refname)) {
			$refname = 'refs/heads/master';
		}

		if ($oldrev == str_pad("0", 40, "0")) {
			$commits = $this->Project->Repo->find('all', array(
				'conditions' => array($newrev),
				'limit' => 1
			));
		} elseif ($newrev == str_pad("0", 40, "0")) {
			$this->Commit->create(array(
				'project_id' =>  $this->Project->id,
				'branch' => $refname,
				'message' => "{$refname} removed",
				'chawuser' => $user
			));

			$this->Commit->save();
		} else {
			$commits = $this->Project->Repo->find('all', array(
				'conditions' => array($oldrev . '..' . $newrev),
				'order' => 'asc'
			));
		}

		if (!empty($commits)) {
			
			foreach ($commits as $data) {
				$this->Commit->create(array(
					'project_id' =>  $this->Project->id,
					'branch' => $refname,
					'chawuser' => $user
				));

				$this->Commit->save($data['Repo']);
			}
		}

		return;
	}

}