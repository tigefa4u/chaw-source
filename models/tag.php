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
class Tag extends AppModel {

	var $name = 'Tag';

	var $validate = array('name' => VALID_NOT_EMPTY);

	var $hasAndBelongsToMany = array('Ticket');

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

	function toString($data = array()) {
		if (empty($data)) {
			return null;
		}
		$cTag = Set::extract($data, '/name');
		return join(', ', array_reverse((array)$cTag));
	}

}
?>