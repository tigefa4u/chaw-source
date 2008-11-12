<?php
/* SVN FILE: $Id$ */
/* User Test cases generated on: 2008-10-16 10:10:23 : 1224177023*/
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
	var $User = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function start() {
		parent::start();
		Configure::write('Content', array(
			'base' => TMP . 'tests' . DS,
			'git' => TMP . 'tests' . DS . 'git' . DS,
			'svn' => TMP . 'tests' . DS . 'svn' . DS ,
		));

		$this->AuthorizedKeys = new File(TMP . 'tests/git/repo/.ssh/authorized_keys', true);

		$this->User =& ClassRegistry::init('User');
	}

	function end() {
		parent::end();
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
	}

	function testUserInstance() {
		$this->assertTrue(is_a($this->User, 'User'));
	}

	function testUserSave() {
		/*
		$result = $this->User->saveKey('gwoo', 'ssh-dss something something else');
		$this->assertTrue($result);

		$result = trim($this->AuthorizedKeys->read());
		$expected = 'command="../../chaw git_shell $SSH_ORIGINAL_COMMAND -user gwoo",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ssh-dss something something else';
		$this->assertEqual($result, $expected);

		$result = $this->User->saveKey('gwoo', 'ssh-dss something something else');
		$this->assertTrue($result);

		$result = trim($this->AuthorizedKeys->read());
		$expected = 'command="../../chaw git_shell $SSH_ORIGINAL_COMMAND -user gwoo",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ssh-dss something something else';
		$this->assertEqual($result, $expected);

		$result = $this->User->saveKey('nate', 'ssh-dss something else');
		$this->assertTrue($result);

		$result = trim($this->AuthorizedKeys->read());
		$expected = 'command="../../chaw git_shell $SSH_ORIGINAL_COMMAND -user gwoo",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ssh-dss something something else';
		$expected .= "\n";
		$expected .= 'command="../../chaw git_shell $SSH_ORIGINAL_COMMAND -user nate",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ssh-dss something else';
		$this->assertEqual($result, $expected);

		$result = $this->User->saveKey('nate', 'ssh-dss something else');
		$this->assertTrue($result);

		$result = trim($this->AuthorizedKeys->read());

		$expected = 'command="../../chaw git_shell $SSH_ORIGINAL_COMMAND -user gwoo",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ssh-dss something something else';
		$expected .= "\n";
		$expected .= 'command="../../chaw git_shell $SSH_ORIGINAL_COMMAND -user nate",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ssh-dss something else';
		$this->assertEqual($result, $expected);
		*/

	}
}
?>