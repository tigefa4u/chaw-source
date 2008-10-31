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
 * @subpackage		chaw.models
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class Project extends AppModel {

	var $name = 'Project';

	var $Repo;

	var $repoTypes = array('Git', 'Svn');

	var $messages = array('response' => null, 'debug' => null);

	var $validate = array(
		'name' => array(
			'required' => array('rule' => 'notEmpty'),
			'unique' => array('rule' => 'isUnique')
		)
	);

	var $hasMany = array('Permission');


	function initialize($params = array()) {
		$this->recursive = -1;
		$this->config = array(
			'id' => null,
			'name' => Inflector::humanize(Configure::read('App.dir')),
			'url' => null,
			'repo_type' => 'Git',
			'private' => 0,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'active' => 1
		);

		$duration = '+99 days';
		if (Configure::read() > 1) {
			$duration = '+15 seconds';
		}

		Cache::set(array('prefix' => 'config_', 'duration' => $duration));

		if (!empty($params['project'])) {
			$key = $params['project'];
			$project = Cache::read($key);
			if (empty($project)) {
				$project = $this->findByUrl($params['project']);
				if (!empty($project)) {
					Cache::write($key, $project);
				}
			}
		} else {
			$key = Configure::read('App.dir');
			$project = Cache::read($key);
			if (empty($project)) {
				$project = $this->find('first');
				if (!empty($project)) {
					Cache::write($key, $project);
				}
			}
		}
		if (!empty($this->data)) {
			$project['Project'] = array_merge($project['Project'], $this->data['Project']);
		}

		if (empty($project)) {
			Configure::write('Project', $this->config);
			return false;
		}

		$this->config = array_merge($this->config, $project['Project']);

		$repoType = strtolower($this->config['repo_type']);
		$path = Configure::read("Content.{$repoType}");

		$this->config['repo'] = array(
			'path' => $path . 'repo' . DS . $this->config['url'],
			'type' => $repoType,
			'working' => $path . 'working' . DS . $this->config['url'],
		);

		if ($repoType == 'git') {
			$this->config['repo']['path'] .= '.git';
		}

		$this->id = $this->config['id'];
		Configure::write('Project', $this->config);

		$this->Repo = ClassRegistry::init(ucwords($repoType));

		$this->Repo->config(array(
			'repo' => $this->config['repo']['path'],
			'working' => $this->config['repo']['working']
		));

		return true;
	}

	function beforeSave() {
		if (!empty($this->data['Project']['name'])) {
			$this->data['Project']['url'] = Inflector::slug(strtolower($this->data['Project']['name']));
		}

		if ($this->initialize() === false) {
			return false;
		}

		if ($this->Repo->create($this->config['url'], array('remote' => 'git@thechaw.com')) !== true) {
			$this->invalidate('repo_type');
			return false;
		}

		return true;
	}

	function afterSave($created) {
		$hooks = array(
			'Git' => array('post-receive'),
			'Svn' => array('pre-commit', 'post-commit')
		);

		$project = $this->data['Project']['url'];
		$chaw = APP . 'content' . DS;

		foreach ($hooks[$this->config['repo_type']] as $hook) {
			if (!file_exists("{$this->Repo->repo}/hooks/{$hook}")) {
				$this->Repo->hook($hook, array('project' => $project, 'chaw' => $chaw));
			}

			if ($created) {
				if ($hook === 'post-commit') {
					$this->Repo->execute("env - {$this->Repo->repo}/hooks/{$hook} {$this->Repo->repo} 1");
				}

				if ($hook === 'post-receive') {
					$this->Repo->execute("env - {$this->Repo->repo}/hooks/{$hook} refs/heads/master");
				}
			}
		}

		$this->messages = array('response' => $this->Repo->response, 'debug' => $this->Repo->debug);

		if (!empty($this->data['Project']['user_id'])) {
			$this->Permission->create(array('user_id' => $this->data['Project']['user_id']));
			$this->Permission->save();
		} else {
			$this->Permission->saveFile($this->config);
		}

		$this->createShell();
	}

	function createShell($data = array()) {

		$path = CONFIGS . 'templates' . DS;

		if (file_exists($path . 'chaw')) {
			$chaw = APP . 'content' . DS;
			$console = array_pop(Configure::corePaths('cake')) . 'console' . DS;
			ob_start();
			include($path . 'chaw');
			$data = ob_get_clean();

			$File = new File($chaw . 'chaw', true, 0775);
			chmod($File->pwd(), 0775);
			return $File->write($data);
		}

		return false;
	}

	function isUnique($data, $options = array()) {
		if (!empty($data['name'])) {
			if ($this->findByName($data['name'])) {
				return false;
			}
			return true;
		}
		if (!empty($data['url'])) {
			if ($this->findByUrl($data['url'])) {
				return false;
			}
			return true;
		}
	}

	function ticket($key = null) {
		switch ($key) {
			case 'types':
				$types = array_map('trim', explode(',', $this->config['ticket_types']));
				return array_combine($types, $types);
			break;
			case 'priorities':
				$priorities = array_map('trim', explode(',', $this->config['ticket_priorities']));
				return array_combine($priorities, $priorities);
			break;
			case 'statuses':
				$statuses = array_map('trim', explode(',', $this->config['ticket_statuses']));
				return array_combine($statuses, $statuses);
			break;
		}
	}

	function groups($key = null) {
		$Inflector = Inflector::getInstance();
		$groups = explode(',', $this->config['groups']);
		$groups = array_map(array($Inflector, 'slug'), $groups, array_fill(0, count($groups), '-'));
		return array_combine($groups, $groups);
	}

	function repoTypes() {
		return array_combine($this->repoTypes, $this->repoTypes);
	}
}
?>