<?php
/**
 * Short description
 *
 * Long description
 *
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Licensed under The MIT License
 * Redistributions of files must retain the copyright notice.
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.models
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
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