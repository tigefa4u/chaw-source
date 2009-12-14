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
class Tag extends AppModel {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Tag';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $validate = array('name' => VALID_NOT_EMPTY);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $hasAndBelongsToMany = array('Ticket');

	/**
	 * undocumented function
	 *
	 * @param string $string
	 * @return void
	 */
	function generate($string = null) {
		$return = array();
		if($string) {
			$array = explode(',', $string);
			foreach($array as $tag) {
			 	if(!empty($tag)) {
					$this->data[$this->alias]['name'] = trim($tag);
					$this->data[$this->alias]['key'] = Inflector::slug($this->data[$this->alias]['name']);
					$this->recursive = -1;
					$existing = $this->findByKey($this->data[$this->alias]['key'], array($this->alias.'.'.$this->primaryKey));
					if(!empty($existing)) {
						$return[] = $existing[$this->alias][$this->primaryKey];
					} else {
						$this->id = null;
						if($this->save($this->data)) {
							$return[] = $this->id;
						}
					}
				}
			}
		}

		return $return;
	}

	/**
	 * undocumented function
	 *
	 * @param string $data
	 * @return void
	 */
	function toString($data = array()) {
		if (empty($data)) {
			return null;
		}
		$cTag = Set::extract('/name', $data);
		return join(', ', array_reverse((array)$cTag));
	}

}
?>