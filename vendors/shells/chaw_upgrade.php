<?php
App::import('Model', 'Schema');
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
class ChawUpgradeShell extends Shell {

	var $uses = array('Project');

	function _welcome() {
		$this->out("Chaw Upgrade");
	}

	function main() {
		$choice = '';
		$Tasks = new Folder(dirname(__FILE__) . DS . 'tasks');
		list($folders, $files) = $Tasks->read();
		$i = 1;
		foreach ($files as $file) {
			if (preg_match('/upgrade_/', $file)) {
				$choices[] = basename($file, '.php');
				$this->out($i . '. ' . basename($file, '.php'));
			}
		}

		while ($choice == '') {
			$choice = $this->in("Enter a number from the list above, or 'q' to exit", null, 'q');

			if ($choice === 'q') {
				$this->out("Exit");
				$this->_stop();
			}

			if ($choice == '' || intval($choice) > count($choices)) {
				$this->err("The number you selected was not an option. Please try again.");
				$choice = '';
			}
		}

		if (intval($choice) > 0 && intval($choice) <= count($choices)) {
			$upgrade = Inflector::classify($choices[intval($choice) - 1]);
		}

		$this->tasks = array($upgrade);
		$this->loadTasks();

		return $this->{$upgrade}->execute();
	}


	function _updateSchema(&$Model, $table, $new = false) {
		$Schema = new CakeSchema(array(
			'name' => 'Chaw',
		));
		$Schema = $Schema->load();

		$db = ConnectionManager::getDataSource($Model->useDbConfig);
		$sql = array();
		if ($new == false) {
			$oldTable = 'old_' . $table;
			$Schema->tables['old_' . $table] = $Model->schema();
			$sql = array(
				$db->dropSchema($Schema, $oldTable),
				$db->createSchema($Schema, $oldTable)
			);
		}
		$db->cacheSources = false;

		$sql = array_merge($sql, array(
			$db->dropSchema($Schema, $table),
			$db->createSchema($Schema, $table)
		));

		$result = false;
		foreach ($sql as $message => $query) {
			if (!empty($this->params['dry'])) {
				$this->out($query);
				continue;
			}
			if ($db->execute($query)) {
				$this->out($query);
				$result = true;
			} else {
				$result = false;
			}
		}

		return $result;
	}

}
