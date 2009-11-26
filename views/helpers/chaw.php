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
class ChawHelper extends AppHelper {

	var $helpers = array('Html');
/**
 * Only returns the link if current user an admin or owner
 *
 * @see HtmlHelper::link()
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
 * Displays humanized type as title and adds type to url
 * makes the css class active if passedArgs['type'] matches $type
 *
 * @see HtmlHelper::link()
 * @param string $type
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
 * display unordered list of messages
 *
 * @param array $messages data(type => array(messages))
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
 * returns commit id, git format: xxxx...xxxx
 * if array $project, returns a link
 *
 * @param string $revision
 * @param string $project [optional]
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

		if (empty($project)) {
			return $title;
		}

		$url = array(
			'admin' => false,
			'controller' => 'commits', 'action'=> 'view', $revision
		);

		if (!empty($project) && $project !== true) {
			$url = array_merge($url, $this->params($project));
		}

		return $this->Html->link($title, $url, array(
			'class' => 'commit', 'title' => $revision
		));
	}
/**
 * toggles activate/deactivate, on/off wrapped in links
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
 * Grab url params from $data
 *
 * @param array $data
 * @return array keys: project, fork
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
 * merges self::params() with $url
 *
 * @param array $data
 * @param array $url
 * @return array merged $url
 */
	function url($data = array(), $url = array()) {
		if (!empty($data)) {
			$url = array_merge($url, $this->params($data));
		}
		return $url;
	}
/**
 * returns base for the current project
 *
 * @param array $params
 * @return array merged $url
 */
	function base($params = array()) {
		$params = array_merge(
			array('project' => null, 'fork' => null), $this->params, $params
		);
		if (!empty($params['url']) && is_string($params['url'])) {
			$params['project'] = $params['url'];
		}

		if (!empty($params['id']) && $params['id'] == 1) {
			$params['project'] = null;
		}

		$fork = null;
		if (!empty($params['fork'])) {
			$fork = 'forks/' . $params['fork'] . '/';
		}
		$base = str_replace("//", "/", $this->base . '/' . $fork . $params['project'] . '/');
		return $base;
	}
/**
 * Display breadcrumbs for using data from Wiki
 *
 * @param string $path the wiki path
 * @param string $slug the wiki page name
 * @return string
 */
	function breadcrumbs($path, $slug = null, $options = array()) {
		$defaults = array('separator' => ' > ', 'ending' => ' . ');
		$options += $defaults;
		$out = array();
		$parts = array_filter(explode('/', $path));

		$rss = null;
		if ($path && $slug !== 'home') {
			$out[] = $this->Html->link('home', array('controller' => 'wiki', 'action' => 'index', '/'));
			$rss = $options['ending'] . $this->rss('home', array('controller' => 'wiki', 'action' => 'index', '/', $path, 'ext' => 'rss'));
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
			if ($path == '/') {
				$parts[] = $slug;
			}
			$parts['action'] = 'index';
			$parts['ext'] = 'rss';
			$rss = $options['ending'] . $this->rss($path, $parts);
		}
		return join($options['separator'], $out) . $rss;
	}
/**
 * Displays feed-icon.png and links to $url
 *
 * @param string $title
 * @param array $url
 * @return string
 */
	function rss($title, $url) {
		return $this->Html->link(
			$this->Html->image('feed-icon.png', array(
				'width' => 14, 'height' => 14
			)),
			$url, array('title' => $title, 'class' => 'rss', 'escape'=> false)
		);
	}

/**
 * Displays list of changes
 *
 * @param string $changes key:value pair separated by newline
 * @param string $format [optional] html, txt [default] html
 * @return string
 */
	function changes($changes, $format = 'html') {
		$results = array();
		$lines = explode("\n", $changes);
		foreach ($lines as $line) {
			$change = null;
			list($field, $value) = explode(":", $line);
			if ($field == 'description') {
				$change = "was changed";
			} elseif (empty($value)) {
				$change = "was removed";
			} else {
				$change = "was changed to";
			}
			$result = null;
			if ($format == 'html') {
				if (empty($value)) {
					$result = sprintf('<li><strong>%1$s</strong> %2$s</li>', $field, $change);
				} else {
					$result = sprintf('<li><strong>%1$s</strong> %2$s <em>%3$s</em></li>', $field, $change, $value);
				}
			} else {
				$result = $field . ' ' . $change . ' ' . $value;
			}
			$results[] = $result;
		}
		if ($format == 'html') {
			return '<ul>' . join("\n", $results) . '</ul>';
		}
		return join("\n", $results);
	}
}