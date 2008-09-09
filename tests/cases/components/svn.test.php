<?php
App::import('Component', 'Svn');

class SvnTest extends CakeTestCase {

	function setUp() {
		$this->Svn = new SvnComponent();
		$this->Svn->config(array('path' => 'file://Volumes/Home/htdocs/cake/repo/branches/1.2.x.x'));
	}
	
	function tearDown() {
		pr($this->Svn->trace());
	}
	
	function testCheckout() {
		//pr($this->Svn->checkout('https://svn.cakephp.org/repo/branches/1.2.x.x'));
	}
	
	function testInfo() {
		pr($this->Svn->info());
	}
	
	function testBlame() {
		pr($this->Svn->blame('index.php'));
	}
	
	function testLs() {
		pr($this->Svn->ls());
	}
	
	function testAdmin() {
	}
	
}
?>