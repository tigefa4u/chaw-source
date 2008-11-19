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
class Comment extends AppModel {

	var $name = 'Comment';

	var $belongsTo = array('User', 'Ticket');

/*
	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Comment\'')
		)
	);
*/
	function afterSave($created) {
		$Timeline = ClassRegistry::init('Timeline');
		
		if ($created) {
			$timeline = array('Timeline' => array(
				'project_id' => $this->data['Comment']['project_id'],
				'model' => 'Comment',
				'foreign_key' => $this->id,
			));

			$Timeline->create($timeline);
			$Timeline->save();
		}
	}

}
?>