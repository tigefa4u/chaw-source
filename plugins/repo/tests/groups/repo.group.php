<?php
/** RepoGroup
 *
 * This test group will run all the Cache class test and all core cache engine tests
 *
 * @package       chaw.plugins.tests
 * @subpackage    chaw.plugins.tests.groups
 */
class RepoGroupTest extends GroupTest {
/**
 * label property
 *
 * @access public
 */
	var $label = 'Test All Repo Types';
/**
 * RepoGroupGroupTest method
 *
 * @access public
 * @return void
 */
	function RepoGroupTest() {
		$path = dirname(dirname(__FILE__));
		TestManager::addTestCasesFromDirectory($this, $path . DS . 'cases' . DS . 'models');
	}
}