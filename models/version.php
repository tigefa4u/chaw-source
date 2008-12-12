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
 * @subpackage		chaw.models
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class Version extends AppModel {

	var $name = 'Version';

	var $belongsTo = array('Project');

	var $validate = array(
		'title' => array('notEmpty')
	);

	function beforeSave() {
		$this->data['Version']['slug'] = Inflector::slug($this->data['Version']['title'], '-');
		return true;
	}
}
?>