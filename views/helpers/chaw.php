<?php

class ChawHelper extends AppHelper {

	var $helpers = array('Html');
/**
 * undocumented function
 *
 * @param string $title
 * @param string $url
 * @param string $htmlAttributes
 * @param string $confirmMessage
 * @param string $escapeTitle
 * @return string
 */
	function admin($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		if (!empty($this->params['isAdmin']) || !empty($this->params['isOwner'])) {
			return $this->Html->link($title, $url, $htmlAttributes, $confirmMessage, $escapeTitle);
		}
		return null;
	}
/**
 * undocumented function
 *
 * @param string $title
 * @param string $url
 * @param string $htmlAttributes
 * @param string $confirmMessage
 * @param string $escapeTitle
 * @return void
 *
 **/
	function type($type, $url = array(), $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		$view = ClassRegistry::getObject('view');
		$passedArgs = $view->passedArgs;

		if (is_array($type)) {
			extract($type);
		}
		if (!empty($type)) {
			$type = Inflector::underscore($type);
			$url['type'] = $type;
		}
		if (empty($title)) {
			$title = Inflector::humanize($type);
		}

		if (array_key_exists('type', $passedArgs) && $passedArgs['type'] == $type) {
			$htmlAttributes['class'] = 'active';
		}
		return $this->Html->link($title, $url, $htmlAttributes, $confirmMessage, $escapeTitle);
	}
/**
 * undocumented function
 *
 * @param string $messages
 * @return string
 */
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
/**
 * undocumented function
 *
 * @param string $revision
 * @param string $project
 * @return string
 */
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
/**
 * undocumented function
 *
 * @param string $value
 * @param string $options
 * @return string
 */
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

/**
 * undocumented function
 *
 * @param string $data
 * @return array
 */
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
/**
 * undocumented function
 *
 * @param string $data
 * @param string $url
 * @return array
 */
	function url($data = array(), $url = array()) {
		if (!empty($data)) {
			$url = array_merge($url, $this->params($data));
		}
		return $url;
	}
/**
 * undocumented function
 *
 * @param string $path
 * @param string $slug
 * @return string
 */
	function breadcrumbs($path, $slug = null) {
		$out = array();
		$parts = array_filter(explode('/', $path));

		$rss = null;
		if ($path && $slug !== 'home') {
			$out[] = $this->Html->link('home', array('controller' => 'wiki', 'action' => 'index', '/'));
			$rss = ' . ' . $this->rss('home', array('controller' => 'wiki', 'action' => 'index', '/', $path, 'ext' => 'rss'));
		}

		if ($path != '/home') {
			foreach ($parts as $key => $part) {
				$parts['action'] = 'index';
				$url[] = $part;
				$out[] = $this->Html->link($part, $url);
			}
		}

		if ($slug) {


			$out[] = $slug;
			$parts[] = $slug;
			$parts['action'] = 'index';
			$parts['ext'] = 'rss';
			$rss = ' . ' . $this->rss($path, $parts);
		}
		return join(' > ', $out) . $rss;
	}
/**
 * undocumented function
 *
 * @param string $title
 * @param string $url
 * @return string
 */
	function rss($title, $url) {
		return $this->Html->link(
			$this->Html->image('feed-icon.png', array(
				'width' => 14, 'height' => 14
			)),
			$url, array(
			'title' => $title, 'class' => 'rss', 'escape'=> false
		));
	}

/**
 * undocumented function
 *
 * @param string $title
 * @param string $url
 * @return string
 */
	function changes($changes) {
		$result = array();
		$lines = explode("\n", $changes);
		foreach ($lines as $line) {
			$change = null;
			list($field, $value) = explode(":", $line);
			if ($field == 'description') {
				$change = "<li><b>{$field}</b> was changed</li>";
			} elseif (empty($value)) {
				$change = "<li><b>{$field}</b> was removed</li>";
			} else {
				$change = "<li><b>{$field}</b> was changed to <em>{$value}</em></li>";
			}
			if (isset($change)) {
				$result[] = $change;
			}
		}
		return '<ul>' . join("\n", $result) . '</ul>';
	}
}