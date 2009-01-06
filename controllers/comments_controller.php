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
 * @subpackage		chaw.controllers
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class CommentsController extends AppController {

	var $name = 'Comments';

	function delete($id = null) {
		if (empty($this->params['isAdmin'])) {
			$this->Session->setFlash('Authorization Failed');
			$this->redirect($this->referer());
		}

		$comment = $this->Comment->find(array('Comment.id' => $id, 'Ticket.project_id' => $this->Project->id));

		if (empty($comment)) {
			$this->Session->setFlash('Invalid Comment');
			$this->redirect($this->referer());
		}

		$this->Comment->bindModel(array('hasOne' => array(
			'Timeline' => array(
				'className' => 'Timeline',
				'foreignKey' => 'foreign_key',
				'conditions' => array('Timeline.model = \'Comment\''),
				'dependent' => true
		))), false);

		if ($this->Comment->del($id)) {
			$this->Session->setFlash('The comment was deleted');
		} else {
			$this->Session->setFlash('The comment was NOT deleted');
			if ($timeline = $this->Comment->Timeline->find('id', array('Timeline.foreign_key' => $id, 'Timeline.model' => 'Comment'))) {
				if ($this->Comment->Timeline->del($timeline)) {
					$this->Session->setFlash('The comment was removed from timeline');
				}
			}
		}
		$this->redirect($this->referer());
	}
}
?>