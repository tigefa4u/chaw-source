<?php

class StateMachineBehavior extends ModelBehavior {

	function setup(&$model, $config) {
		$defaults = array(
			'state' => 'state', 'default' => null, 'states' => array(), 'events' => array()
		);
		$this->settings[$model->name] = ($config += $defaults);

		if (empty($config['events'])) {
			$message = 'StateMachineBehavior::setup() - You must define at least one event';
			trigger_error($message, E_USER_WARNING);
			return false;
		}
		$this->mapMethods['/^(' . join('|', $config['events']) . ')$/'] = 'transitions';
	}

	function transition(&$model, $from, $to) {
		$state = $model->field($this->settings[$model->name]['state']);

		if (in_array($state, (array)$from)) {
			return $model->saveField($this->settings[$model->name]['state'], $to);
		}
		return false;
	}

	function transitions($event) {
		if (is_object($event)) {
			$message = "StateMachineBehavior::transitions() - You must define {$event->name}";
			$message .= "::(\$event) in your {$event->name} model before continuing";
			trigger_error($message, E_USER_WARNING);
			return false;
		}

		
	}

	function beforeValidate(&$model) {
		
	}
}

?>