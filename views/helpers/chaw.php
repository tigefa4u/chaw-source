<?php

class ChawHelper extends AppHelper {

	var $helpers = array('Html');

	function admin($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		if (!empty($this->params['isAdmin']) || !empty($this->params['isOwner'])) {
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

	function commit($revision = null, $project = array()) {
		if (!$revision) {
			return null;
		}

		$title = $revision;

		if (strlen($revision) > 10) {
			$title = substr($revision, 0, 4) .'...' . substr($revision, -4, 4);
		}

		$url = array(
			'admin' => false,
			'controller' => 'commits', 'action'=> 'view', $revision
		);

		if (!empty($project)) {
			$url = array_merge($url, $this->params($project));
		}

		return $this->Html->link($title, $url, array(
			'class' => 'commit', 'title' => $revision
		));
	}

	function toggle($value, $options) {
		if (!empty($options['url'])) {
			$url = $options['url'];
			unset($options['url']);
		}

		$option = $options[0];
		if ($value == 1) {
			$option = $options[1];
		}

		$url = array_merge((array)$url, array('action' => $option));

		return $this->Html->link($option, $url, array('class' => 'toggle', 'title' => $option));
	}

	function params($data = array()) {
		if (!empty($data['Project'])) {
			$data = $data['Project'];
		}

		$project = null;
		if ($data['id'] != 1) {
			$project = $data['url'];
		}

		$fork = null;
		if (!empty($data['fork'])) {
			$fork = $data['fork'];
		}

		return compact('project', 'fork');
	}

	function url($data = array(), $url = array()) {
		if (!empty($data)) {
			$url = array_merge($url, $this->params($data));
		}
		return $url;
	}

	function breadcrumbs($path, $slug) {
		$out = array();
		$parts = array_filter(explode('/', $path));

		if ($slug !== 'home') {

			$out[] = $this->Html->link('home', array('controller' => 'wiki', '/'));

			foreach ($parts as $key => $part) {
				$out[] = $this->Html->link($part, $parts);
			}

			$out[] = $slug;
			return join(' > ', $out) ;
		}

		return null;
	}
}