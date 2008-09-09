<?php
class BrowserController extends AppController {

	var $name = 'Browser';

	function index() {
		$args = func_get_args();
		$path = join(DS, $args);

		$current = null;
		
		if ($args > 0) {
			$current = array_pop($args);
		}

		$data = $this->Browser->read($path);

		$this->set(compact('data', 'args', 'current'));
	}
	
	function tree($branch = 'HEAD') {
		$Git = ClassRegistry::init('Git');
		$Git->repo = '/htdocs/scm/creampuff.git';
		$Git->workingCopy = TMP . 'git';
		
		//pr($Git->tree($branch));
		
		//pr($Git->sub('cat-file', array('-t 0ed8662ea6402467a50a6e515042485227c2dd0c')));
		
		pr($Git->info($branch));
		
		pr($Git->debug);
		
		die();
	}
}
?>