<?php
class TimelineController extends AppController {

	var $name = 'Timeline';
	
	var $paginate = array('order' => 'Timeline.created DESC');
	
	function index() {
		$this->Timeline->recursive = 1;
		$conditions = null;
		
		if (!empty($this->params['project'])) {
			$conditions = array('Timeline.project_id' => $this->Project->id);
		}
		
		$this->set('timeline', $this->paginate('Timeline', $conditions));
	}
	
	function sync() {
		
	}
}
?>