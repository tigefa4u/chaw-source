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
	}

	function testChawInstance() {
		$this->assertTrue(is_a($this->Chaw, 'ChawHelper'));
	}

	function testUrl() {
		$result = $this->Chaw->params(array('id' => 1, 'url' => 'chaw', 'fork' => null));
		$expected = array('project' => null, 'fork' => null);
		$this->assertEqual($result, $expected);

		$result = $this->Chaw->params(array('id' => 2, 'url' => 'some_project', 'fork' => null));
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
		

	}

}
?>