<?php

class AdminHelper extends AppHelper {

	var $helpers = array('Html');

	function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		if (!empty($this->params['isAdmin'])) {
			return $this->Html->link($title, $url, $htmlAttributes, $confirmMessage, $escapeTitle);
		}
		return null;
	}
}