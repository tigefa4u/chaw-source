<?php
/**
 * Short description
 *
 * Long description
 *
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Licensed under The MIT License
 * Redistributions of files must retain the copyright notice.
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class AppError extends ErrorHandler {
/**
 * Controller instance.
 *
 * @var object
 * @access public
 */
	var $controller = null;
/**
 * Class constructor.
 *
 * @param string $method Method producing the error
 * @param array $messages Error messages
 */
	function __construct($method, $messages) {
		App::import('Core', 'Sanitize');
		static $__previousError = null;

		if ($__previousError != array($method, $messages)) {
			$__previousError = array($method, $messages);
			$this->controller =& new Controller();
			$this->controller->helpers = array('Html', 'Form', 'Javascript', 'Admin');
			$this->controller->viewPath = 'errors';
		} else {
			$this->controller =& new Controller();
			$this->controller->helpers = array('Html', 'Form', 'Javascript', 'Admin');
			$this->controller->viewPath = 'errors';
		}

		$options = array('escape' => false);
		$messages = Sanitize::clean($messages, $options);

		if (!isset($messages[0])) {
			$messages = array($messages);
		}

		if (method_exists($this->controller, 'apperror')) {
			return $this->controller->appError($method, $messages);
		}

		if (!in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))) {
			$method = 'error';
		}

		if ($method !== 'error') {
			if (Configure::read() == 0){
				$method = 'error404';
				if(isset($code) && $code == 500) {
					$method = 'error500';
				}
			}
		}

		if (($method !== 'missingAction' || $method !== 'missingController')
			&& $this->controller->here !== $this->controller->base . '/admin/install') {
			if (defined('CAKE_TEST_OUTPUT')) {
				$this->controller->layout = 'ajax';
			}
			$this->dispatchMethod($method, $messages);
			$this->_stop();
		}
	}
}
?>