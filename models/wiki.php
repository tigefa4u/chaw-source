<?php
class Wiki extends AppModel {

	var $name = 'Wiki';
	
	var $useTable = 'wiki';
	
	
	var $validate = array('content' => array('notEmpty'));
	
	
	function beforeSave() {
		ini_set('include_path', APP . 'vendors' . DS . 'Pear' . PATH_SEPARATOR . ini_get('include_path'));
		App::import('Vendor', 'Text_Wiki_Creole', array('file' => 'Text/Wiki/Creole.php'));
		$TextWiki = new Text_Wiki_Creole();
		$this->data['Wiki']['content'] = $TextWiki->transform($this->data['Wiki']['content'], 'Xhtml'); 
		return true;
	}
}
?>