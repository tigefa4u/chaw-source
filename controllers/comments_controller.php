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
			$this->Session->setFlash(__('Authorization Failed',true));
			$this->redirect($this->referer());
		}

		$comment = $this->Comment->find(array('Comment.id' => $id, 'Ticket.project_id' => $this->Project->id));

		if (empty($comment)) {
			$this->Session->setFlash(__('Invalid Comment',true));
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
			$this->Session->setFlash(__('The comment was deleted',true));
		} else {
			$this->Session->setFlash(__('The comment was NOT deleted',true));
			if ($timeline = $this->Comment->Timeline->find('id', array('Timeline.foreign_key' => $id, 'Timeline.model' => 'Comment'))) {
				if ($this->Comment->Timeline->del($timeline)) {
					$this->Session->setFlash(__('The comment was removed from timeline',true));
				}
			}
		}
		$this->redirect($this->referer());
	}
}
?>