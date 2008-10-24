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

	var $validate = array(
		'name' => array(
			'required' => array('rule' => 'notEmpty'),
			'unique' => array('rule' => 'isUnique')
		)
	);

	var $repoTypes = array('Git', 'Svn');

	var $messages = array('response' => null, 'debug' => null);

	var $hasMany = array('Permission');

	function initialize($params) {
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

		if (empty($project)) {
			$this->config = array(
				'id' => null,
				'name' => Inflector::humanize(Configure::read('App.dir')),
				'url' => null,
				'repo_type' => 'git',
				'private' => 0,
				'groups' => 'user, docs team, developer, admin',
				'ticket_types' => 'rfc, bug, enhancement',
				'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
				'ticket_priorities' => 'low, normal, high',
				'active' => 1
			);
			Configure::write('Project', $this->config);
			return false;
		}

		$this->config = $project['Project'];

		$repoType = strtolower($this->config['repo_type']);
		$path = Configure::read("Content.{$repoType}");

		$this->config['repo'] = array(
			'path' => $path . 'repo' . DS . $key,
			'type' => $repoType,
			'working' => $path . 'working' . DS . $key,
		);

		if ($repoType == 'git') {
			$this->config['repo']['path'] .= '.git';
		}

		$this->id = $this->config['id'];
		Configure::write('Project', $this->config);
		return true;
	}

	function beforeSave() {
		if (!empty($this->data['Project']['name'])) {
			$this->data['Project']['url'] = Inflector::slug(strtolower($this->data['Project']['name']));
		}
		return true;
	}

	function afterSave($created) {

		$project = $this->data['Project']['url'];
		$repoType = ucwords($this->data['Project']['repo_type']);

		$Repo = ClassRegistry::init($repoType);

		$key = strtolower($repoType);
		$path = Configure::read("Content.{$key}");

		$Repo->config(array('repo' => $path . 'repo', 'working' => $path . 'working'));

		$Repo->create($project, array('remote' => 'git@thechaw.com'));

		$hooks = array(
			'Git' => array('update', 'post-receive'),
			'Svn' => array('pre-commit', 'post-commit')
		);

		foreach ($hooks[$repoType] as $hook) {
			if (!file_exists("{$Repo->repo}/hooks/{$hook}")) {
				$Repo->hook($hook, array('project' => $this->id));
			}

			if ($created) {
				if ($hook === 'post-commit') {
					$Repo->execute("env - {$Repo->repo}/hooks/{$hook} {$Repo->repo} 1");
				}

				if ($hook === 'post-receive') {
					$Repo->execute("env - {$Repo->repo}/hooks/{$hook} refs/heads/master");
				}
			}
		}

		$this->messages = array('repsonse' => $Repo->response, 'debug' => $Repo->debug);
	}

	function isUnique($data, $options = array()) {
		if (!empty($data['name'])) {
			if ($this->findByName($data['name'])) {
				return false;
			}
			return true;
		}
		if (!empty($data['url'])) {
			if ($this->findByName($data['url'])) {
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