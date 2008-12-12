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
class TracShell extends Shell {

/**
 * undocumented class variable
 *
 * @var string
 **/
	var $uses = array('Project', 'Ticket');

	function main() {
		$this->help();
	}

	function migrate() {
		$type = "__{$this->args[0]}";
		if (method_exists($this, $type)) {
			return $this->{$type}();
		}
	}

	function __tickets() {
		$this->out('This may take a while...');
		$project = @$this->args[1];
		$fork = null;
		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->config['url'] !== $project) {
			$this->err('Invalid project');
			return 1;
		}

		$path = $this->args[2];
		$ext = array_pop(explode('.', $path));
		if ($ext == 'xml') {
			App::import('Xml');
			$Xml = new Xml($path);
			$rows = array();

			$this->out('Importing Data...');
			foreach ($Xml->toArray() as $key => $data) {
				foreach ($data['Records']['Row'] as $columns) {
					$new = array();
					foreach ($columns['Column'] as $column) {
						if ($column['name'] == 'created' || $column['name'] == 'modified') {
							$column['value'] = date('Y-m-d H:m:s', $column['value']);
						}
						$new[$column['name']] = $column['value'];
					}
					$new['project_id'] = $this->Project->id;
					$this->Ticket->create($new);
					if ($this->Ticket->save()) {
						$this->out('Ticket ' . $new['number'] .' : ' . $new['title'] . ' migrated');
						sleep(1);
					}
				}
			};

			return 0;
		}

		$File = new File($path);
		$data = explode("\n", $File->read());


		$fields = explode(',', array_shift($data));
		foreach ($fields as $key => $field) {
			$fields[$key] = str_replace('"', '', $field);
		}

		pr($fields);

		$result = array();
		foreach ($data as $line) {
			$values = explode(',', $line);
			foreach ($values as $key => $value) {
				$field = str_replace('"', '', $fields[$key]);
				$result[$field] = str_replace('"', '', $value);
			}
		}
		pr($result);
	}
}