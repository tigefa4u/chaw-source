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
 * @subpackage		chaw.tests.fixtures
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class Branch extends AppModel {

	var $name = 'Branch';

	var $validate = array(
		'name' => array(
			'required' => true, 'rule' => 'notEmpty'
		),
		'project_id' => array(
			'required' => true, 'rule' => 'numeric'
		)
	);

	function beforeSave() {
		$ref = explode('/', $this->data['Branch']['name']);
		$this->data['Branch']['name'] = array_pop($ref);
		$this->data['Branch']['ref'] = join('/', $ref);
		$result = $this->field('id', array(
			'name' => $this->data['Branch']['name'],
			'ref' => $this->data['Branch']['ref'],
			'project_id' => $this->data['Branch']['project_id']
		));
		return empty($result);
	}
}
?>