<?php

class AdminHelper extends AppHelper {

	var $helpers = array('Html');

	function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		if (!empty($this->params['isAdmin'])) {
			return $this->Html->link($title, $url, $htmlAttributes, $confirmMessage, $escapeTitle);
		}
		return null;
	}

	function messages($messages = array()) {
		$result = array();
		foreach((array)$messages as $type => $types) {
			if (!empty($types)) {
				$result[] = $this->Html->tag('h4', $type);
				$list = array();
				foreach ((array)$types as $message) {
					$list[] = $this->Html->tag('li', $message);
				}
				$result[] = $this->Html->tag('ul', join("\n", $list));
			}
		}
		return join("\n", $result);
	}
}