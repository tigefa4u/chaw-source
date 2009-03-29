<?php

class StateMachineBehavior extends ModelBehavior {

	function setup(&$model, $config) {
		$defaults = array(
			'field' => 'state', 'default' => null, 'states' => array(),
			'events' => array(), 'transitions' => array(), 'auto' => false
		);
		$config += $defaults;

		if (empty($config['events']) && !empty($config['transitions'])) {
			$config['events'] = array_keys($config['transitions']);
		}

		if (empty($config['events'])) {
			$message = 'StateMachineBehavior::setup() - You must define at least one event';
			trigger_error($message, E_USER_WARNING);
			return false;
		}
		$this->settings[$model->name] = ($config += $defaults);
		$this->mapMethods['/^(' . join('|', $config['events']) . ')$/'] = 'event';
	}

	function transition(&$model, $from, $to = null) {
		$state = $model->field($this->settings[$model->name]['field']);
		if (in_array($state, (array)$from)) {
			if (empty($model->data[$model->alias]['event'])) {
				return $model->saveField($this->settings[$model->name]['field'], $to);
			} else {
				$model->data[$model->alias][$this->settings[$model->name]['field']] = $to;
				return true;
			}
		}
		return false;
	}

	function event(&$model, $event) {
		if (!isset($this->settings[$model->name]['transitions'][$event])) {
			return false;
		}
		$settings = $this->settings[$model->name];

		if ($settings['auto'] === false) {
			return $model->transitions($event);
		}

		if ($settings['auto'] == true || $settings['auto'] == 'before') {
			if (!$model->transitions($event)) {
				return false;
			}
		}
		$success = false;

		if (isset($settings['transitions'][$event])) {
			foreach ($settings['transitions'][$event] as $from => $to) {
				if ($this->transition($model, $from, $to)) {
					$success = true;
					break;
				}
			}
		}

		if ($success && $settings['auto'] == 'after') {
			return $model->transitions($event);
		}
		return $success;
	}

	/**
	 * If 'transitions' is defined in settings, returns the possible transition events for the
	 * current state.
	 *
	 * @return array
	 */
	function events(&$model, $state = null) {
		$settings = $this->settings[$model->name];
		$state = empty($state) ? $model->field($settings['field']) : $state;
		$results = array();

		if (empty($settings['transitions'])) {
			return array();
		}
		foreach ($settings['transitions'] as $event => $transitions) {
			if (isset($transitions[$state])) {
				$results[$event] = $event;
			}
		}
		return $results;
	}

	/**
	 * Must be overridden in the attached model class to handle state transitions.  I was going to
	 * do something else with this, but I can't remember what.
	 *
	 * @param string $event
	 * @return void
	 */
	function transitions($event = null) {
		if (is_object($event)) {
			$message = "StateMachineBehavior::transitions() - You must define {$event->name}";
			$message .= "::(\$event) in your {$event->name} model before continuing";
			trigger_error($message, E_USER_WARNING);
		}
		return false;
	}

	function beforeValidate(&$model) {
		if ($model->exists()) {
			return true;
		}
		$settings = $this->settings[$model->name];

		if (empty($model->data[$model->alias][$settings['field']])) {
			$model->set($settings['field'], $settings['default']);
		}
		return true;
	}
}

?>