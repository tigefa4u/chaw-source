<?php
/* SVN FILE: $Id$ */
/* Project Test cases generated on: 2008-10-06 15:10:20 : 1223321240*/
class ProjectTestCase extends CakeTestCase {
	var $Project = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit', 'app.branch',
		'app.branch_commit'
	);

	function startTest() {
		Configure::write('Content', array(
			'base' => TMP . 'tests' . DS,
			'git' => TMP . 'tests' . DS . 'git' . DS,
			'svn' => TMP . 'tests' . DS . 'svn' . DS ,
		));
		Configure::write('Project', array(
			'id' => 0,
			'remote' => 'git@git.chaw'
		));
		$this->Project = ClassRegistry::init('Project');
	}

	function endTest() {
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}

		$MoreCleanup = new Folder(TMP . 'tests/svn');
		if ($MoreCleanup->pwd() == TMP . 'tests/svn') {
			$MoreCleanup->delete();
		}
		$this->__cleanUp();
	}

	function testProjectInstance() {
		$this->assertTrue(is_a($this->Project, 'Project'));
	}

	function testProjectSave() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$path = Configure::read('Content.base');
		$this->assertTrue(file_exists($path . 'permissions.ini'));
		$this->assertFalse(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));

		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'test project',
			'username' => 'gwoo',
			'user_id' => 1,
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'hooks' . DS . 'post-receive'));


		$data = array('Project' =>array(
			'id' => 3,
			'name' => 'svn project',
			'username' => 'gwoo',
			'user_id' => 1,
			'repo_type' => 'Svn',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'hooks' . DS . 'post-commit'));


		$Timeline = ClassRegistry::init('Timeline');
		$result = $Timeline->find('all', array('conditions' => array('Timeline.project_id' => 3)));

		$this->assertEqual($result[0]['Wiki']['slug'], 'home');

	}

	function testProjectEdit() {
		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'new project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$path = Configure::read('Content.base');
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));

		$this->__cleanUp();

		$this->assertFalse(file_exists($this->Project->Repo->path));

		$data = array('Project' =>array(
			'id' => 2,
			'url' => 'new_project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data, false));
		$this->assertTrue(file_exists($this->Project->Repo->path));
	}

	function testProjectFork() {
		$this->__cleanUp();

		//first we have to save a project
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$path = Configure::read('Content.base');
		$this->assertTrue(file_exists($path . 'permissions.ini'));
		$this->assertFalse(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'hooks' . DS . 'post-receive'));

		$result = file_get_contents($path . 'permissions.ini');
		$expected = "[admin]\n@admin = crud\n\n[refs/heads/master]\n@admin = rw";
		$this->assertEqual($result, $expected);

		$results = $this->Project->Permission->find('all', array('conditions' => array('Permission.project_id' => 1)));
		unset($results[0]['Permission']['created'], $results[0]['Permission']['modified']);
		$this->assertEqual($results[0]['Permission'], array('id'=> 1, 'user_id' => 1, 'project_id' => 1, 'group' => 'admin'));

		$this->Project->create(array_merge(
			$this->Project->current,
			array(
				'user_id' => 2,
				'fork' => 'bob',
				'approved' => 1,
			)
		));

		$this->assertTrue($data = $this->Project->fork());
		$this->assertEqual($data['Project']['url'], 'original_project');
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'hooks' . DS . 'post-receive'));
		@unlink($this->Project->Repo->path . DS . 'hooks' . DS . 'post-receive');

		$this->assertTrue($this->Project->save($data));
		$this->assertEqual($data['Project']['url'], 'original_project');
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'hooks' . DS . 'post-receive'));


		$results = $this->Project->Permission->find('all', array('conditions' => array('Permission.project_id' => 1)));
		unset($results[0]['Permission']['created'], $results[0]['Permission']['modified']);
		$this->assertEqual($results[0]['Permission'], array('id'=> 1, 'user_id' => 1, 'project_id' => 1, 'group' => 'admin'));
		unset($results[1]['Permission']['created'], $results[1]['Permission']['modified']);
		$this->assertEqual($results[1]['Permission'], array('id'=> 3, 'user_id' => 2, 'project_id' => 1, 'group' => null));


		$results = $this->Project->Permission->find('all', array('conditions' => array('Permission.project_id' => 2)));
		unset($results[0]['Permission']['created'], $results[0]['Permission']['modified']);
		$this->assertEqual($results[0]['Permission'], array('id'=> 2, 'user_id' => 2, 'project_id' => 2, 'group' => 'admin'));


		$result = $this->Project->field('users_count', array('id' => 1));
		$this->assertEqual($result, 2);

		$result = $this->Project->field('users_count', array('id' => 2));
		$this->assertEqual($result, 1);


		//die();
 		//pr($this->Project->Repo->debug);
		//pr($this->Project->Repo->response);
	}

	function testProjectActivate() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$this->Project->id = 1;
		$this->Project->initialize(array('project' => 'original_project'));
		$this->Project->set($this->Project->current);
		$result = $this->Project->save(array('active' => 1));
		$this->assertTrue($result);

		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'another project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 0,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$this->Project->id = 2;
		$this->Project->initialize(array('project' => 'another_project'));
		$this->Project->set($this->Project->current);
		$result = $this->Project->save(array('active' => 1));
		$this->assertTrue($result);
	}

	function testProjectEditAndUpdateHooks() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
			'username' => 'gwoo',
			'user_id' => 1,
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'test project',
			'username' => 'gwoo',
			'user_id' => 1,
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'hooks' . DS . 'post-receive'));
		@unlink($this->Project->Repo->path . DS . 'hooks' . DS . 'post-receive');

		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'test project',
			'username' => 'gwoo',
			'user_id' => 1,
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'hooks' . DS . 'post-receive'));
	}

	function testAll() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'test project',
			'username' => 'gwoo',
			'user_id' => 1,
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$config = $this->Project->current;

		$this->Project->create(array_merge(
			$config,
			array(
				'user_id' => 1,
				'fork' => 'gwoo',
				'approved' => 1,
			)
		));
		$this->assertTrue($data = $this->Project->fork());
		$this->Project->create(array_merge(
			$config,
			array(
				'user_id' => 2,
				'fork' => 'bob',
				'approved' => 1,
			)
		));
		$this->assertTrue($data = $this->Project->fork());

		$results = Set::extract('/Project/id', $this->Project->all(3));
		$this->assertEqual($results, array('1', '2', '3'));

		$results = Set::extract('/Project/id', $this->Project->all(1, false));
		$this->assertEqual($results, array('2', '3'));

	}

	function testProjectUsers() {
		$this->Project->User->create(array('username' => 'gwoo', 'email' => 'gwoo@test.org'));
		$this->assertTrue($this->Project->User->save());
		$this->Project->User->create(array('username' => 'bob', 'email' => 'bob@test.org'));
		$this->assertTrue($this->Project->User->save(array('username' => 'bob', 'email' => 'bob@test.org')));

		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'test project',
			'username' => 'gwoo',
			'user_id' => 1,
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$config = $this->Project->current;

		$this->Project->create(array_merge(
			$config,
			array(
				'user_id' => 1,
				'fork' => 'gwoo',
				'approved' => 1,
			)
		));
		$this->assertTrue($data = $this->Project->fork());
		$this->Project->create(array_merge(
			$config,
			array(
				'user_id' => 2,
				'fork' => 'bob',
				'approved' => 1,
			)
		));
		$this->assertTrue($this->Project->save());

		$results = $this->Project->users();
		$this->assertEqual($results, array('1'=> 'gwoo', '2' => 'bob'));

		$results = $this->Project->users(array('Permission.group' => 'user'));
		$this->assertEqual($results, array());

		$results = $this->Project->users(array('Permission.group' => array('admin', 'developer')));
		$this->assertEqual($results, array('1'=> 'gwoo', '2' => 'bob'));
	}

	function testApprovedProjectPermissionsCreate() {
		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 0,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$path = Configure::read('Content.base');
		$this->assertFalse(file_exists($path . 'permissions.ini'));

		$project = 'original_project';
		$this->Project->initialize(compact('project'));
		$this->Project->set($this->Project->current);
		$this->assertTrue($this->Project->save(array('approved' => 1)));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));
	}

	function testProjectTicketConfig() {
		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$result = $this->Project->ticket('types');
		$this->assertEqual($result, array('rfc' => 'rfc', 'bug' => 'bug', 'enhancement' => 'enhancement'));

		$result = $this->Project->ticket('statuses');
		$this->assertEqual($result, array(
			'pending' => 'pending', 'approved' => 'approved',
			'in progress' => 'in progress', 'on hold' => 'on hold', 'closed' => 'closed'
		));

		$result = $this->Project->ticket('priorities');
		$this->assertEqual($result, array('low' => 'low', 'normal' => 'normal', 'high' => 'high'));
	}

	function testProjectGroupConfig() {
		$data = array('Project' =>array(
			'id' => 2,
			'active' => 1,
			'approved' => 1,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'description' => 'this is a test project',
			'config' => array(
				'groups' => 'user, docs, team, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$this->assertTrue(file_exists($this->Project->Repo->path));

		$result = $this->Project->groups();
		$this->assertEqual($result, array(
			'user' => 'user', 'docs' => 'docs', 'team' => 'team', 'admin' => 'admin'
		));
	}

	function testProjectInitializeWithId() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$path = Configure::read('Content.base');
		$this->assertTrue(file_exists($path . 'permissions.ini'));
		$this->assertFalse(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));

		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'new project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$path = Configure::read('Content.base');
		$this->assertTrue(file_exists($path . 'permissions.ini'));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));
		
		
		$this->assertEqual(2, $this->Project->id);
		
		$this->assertTrue($this->Project->initialize(array('project' => 'new_project')));
		
		$this->assertEqual(1, $this->Project->current['id']);
	}

	function igetTests() {
		return array('start', 'testProjectTicketConfig', 'end');
	}

	function __cleanUp() {
		$path = Configure::read('Content.base');
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
		@unlink($path . 'chaw');
		@unlink($path . 'permissions.ini');
	}
}
?>
