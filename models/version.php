<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
/**
 * undocumented class
 *
 * @package default
 */
class Version extends AppModel {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Version';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $belongsTo = array('Project');

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $validate = array(
		'title' => array('notEmpty')
	);

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeSave() {
		$this->data['Version']['slug'] = Inflector::slug($this->data['Version']['title'], '-');
		return true;
	}
}
?>