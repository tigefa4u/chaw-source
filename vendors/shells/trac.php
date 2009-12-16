<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
/**
 * 
 * Select id as number, type, milestone as version, reporter, owner, resolution as status, summary as title, description, keywords, time as created, changetime as modified
 */
class TracShell extends Shell {

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	var $uses = array('Project', 'Ticket');
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function main() {
		$this->help();
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function migrate() {
		$type = "__{$this->args[0]}";
		if (method_exists($this, $type)) {
			return $this->{$type}();
		}
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function __tickets() {
		$this->out('This may take a while...');
		$project = @$this->args[1];
		$fork = null;
		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->current['url'] !== $project) {
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
							$column['value'] = date('Y-m-d H:i:s', $column['value']);
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