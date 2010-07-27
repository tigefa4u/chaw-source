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
class Comment extends AppModel {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Comment';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $belongsTo = array(
		'User',
		'Ticket' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Comment.model = \'Ticket\'')
		)
	);

/*
	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Timeline.model = \'Comment\'')
		)
	);
*/
	/**
	 * undocumented function
	 *
	 * @param string $created
	 * @return void
	 */
	function afterSave($created) {
		if ($created && $this->addToTimeline && !empty($this->data['Comment']['project_id'])) {
			$Timeline = ClassRegistry::init('Timeline');
			$timeline = array('Timeline' => array(
				'user_id' => $this->data['Comment']['user_id'],
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