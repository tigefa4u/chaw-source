<?php
class WikiController extends AppController {

	var $name = 'Wiki';

	function index($slug = null) {
		if(count($this->passedArgs) > 1) {
			$slug = join('/', $this->passedArgs);
		}

		$this->pageTitle = Inflector::humanize(Inflector::slug($slug));

		if ($wiki = $this->Wiki->findBySlug($slug)) {
			$this->set('wiki', $wiki);
		} else {
			$this->data['Wiki']['slug'] = $slug;
			$this->render('add');
		}
	}

	function add() {
		if (!empty($this->data)) {
			$this->Wiki->create();
			if ($data = $this->Wiki->save($this->data)) {
				$this->Session->setFlash('Wiki Page created');
				$this->set('wiki', $data);
				$this->render('view');
			} else {
				$this->Session->setFlash('Wiki Page broken');
			}
		}
	}
}
?>