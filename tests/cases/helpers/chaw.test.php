<?php
/* SVN FILE: $Id$ */
/* Chaw Test cases generated on: 2008-11-18 18:11:32 : 1227060572*/
App::import('Helper', 'Chaw');
App::import('Helper', 'Html');

extract(Router::getNamedExpressions());
include(CONFIGS . 'routes.php');

class TestChaw extends ChawHelper {
}

class ChawHelperTest extends CakeTestCase {

	function start() {
		parent::start();
		$this->Chaw = new TestChaw();
		$this->Chaw->Html = new HtmlHelper();
		$this->Chaw->params = array(
			'url' => array('url' => '/', 'ext' => 'html')
		);
		
		$this->Chaw->webroot = '/';
	}

	function testChawInstance() {
		$this->assertTrue(is_a($this->Chaw, 'ChawHelper'));
	}

	function testParams() {
		$result = $this->Chaw->params(array('id' => 1, 'url' => 'chaw', 'fork' => null));
		$expected = array('project' => null, 'fork' => null);
		$this->assertEqual($result, $expected);

		$result = $this->Chaw->params(array('id' => 2, 'url' => 'some_project', 'fork' => null));
		$expected = array('project' => 'some_project', 'fork' => null);
		$this->assertEqual($result, $expected);

	}
	
	function testBase() {
		$result = $this->Chaw->base(array('project' => null, 'fork' => null));
		$expected = '/';
		$this->assertEqual($result, $expected);
		
		$result = $this->Chaw->base(array('id' => 1, 'project' => 'chaw', 'fork' => null));
		$expected = '/';
		$this->assertEqual($result, $expected);

		$result = $this->Chaw->base(array('id' => 2, 'url' => 'some_project', 'fork' => null));
		$expected = '/some_project/';
		$this->assertEqual($result, $expected);		
		
		$result = $this->Chaw->base(array('id' => 2, 'url' => 'some_project', 'fork' => 'gwoo'));
		$expected = '/forks/gwoo/some_project/';
		$this->assertEqual($result, $expected);
		

	}
	
	function testUrl() {
		$result = $this->Chaw->url(array('id' => 1, 'url' => 'chaw', 'fork' => null));
		$expected = array('project' => null, 'fork' => null);
		$this->assertEqual($result, $expected);

		$result = $this->Chaw->url(array('id' => 2, 'url' => 'some_project', 'fork' => null));
		$expected = array('project' => 'some_project', 'fork' => null);
		$this->assertEqual($result, $expected);

	}
	
	function testCommit() {
		$result = $this->Chaw->commit('1111111111111111111111', array('id' => 1, 'url' => 'chaw', 'fork' => null));
		$expected = '<a href="/commits/view/1111111111111111111111" class="commit" title="1111111111111111111111">1111...1111</a>';

		$this->assertEqual($result, $expected);

		$result = $this->Chaw->commit('1111111111111111111111', array('id' => 2, 'url' => 'some_project', 'fork' => null));
		$expected = '<a href="/some_project/commits/view/1111111111111111111111" class="commit" title="1111111111111111111111">1111...1111</a>';

		$this->assertEqual($result, $expected);

		$result = $this->Chaw->commit('1111111111111111111111', true);
		$expected = '<a href="/commits/view/1111111111111111111111" class="commit" title="1111111111111111111111">1111...1111</a>';

		$this->assertEqual($result, $expected);
		
		$result = $this->Chaw->commit('1111111111111111111111', array('id' => 2, 'url' => 'some_project', 'fork' => 'Gwoo1'));
		$expected = '<a href="/forks/Gwoo1/some_project/commits/view/1111111111111111111111" class="commit" title="1111111111111111111111">1111...1111</a>';

		$this->assertEqual($result, $expected);
		
	}

	function testChanges() {
		$changes = "version:new\nstatus:closed";
		$result = $this->Chaw->changes($changes);
		$expected = "<ul><li><strong>version</strong> was changed to <em>new</em></li>\n<li><strong>status</strong> was changed to <em>closed</em></li></ul>";
		$this->assertEqual($result, $expected);

		$changes = "version:\nstatus:closed\nowner:";
		$result = $this->Chaw->changes($changes);
		$expected = "<ul><li><strong>version</strong> was removed</li>\n<li><strong>status</strong> was changed to <em>closed</em></li>\n<li><strong>owner</strong> was removed</li></ul>";
		$this->assertEqual($result, $expected);

	}

}
?>