<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
/**
 * undocumented class
 *
 * @package default
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
			$this->controller =& new CakeErrorController();
		} else {
			$this->controller =& new Controller();
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

		if (!in_array($method, array('error'))) {
			if (Configure::read() == 0) {
				$method = 'error404';
				if (isset($code) && $code == 500) {
					$method = 'error500';
				}
			}
		}

		if (defined('CAKE_TEST_OUTPUT_HTML')) {
			$this->controller->layout = 'ajax';
			debug(Debugger::trace());
		}

		$this->dispatchMethod($method, $messages);
		$this->_stop();
	}

	/**
	 * undocumented function
	 *
	 * @param string $messages
	 * @return void
	 */
	function missingAction($messages = array()) {
		return parent::missingAction($messages);
		/*
		$this->controller->redirect(array_merge(
			array('controller' => 'wiki', 'action' => 'index', $this->controller->params['action']),
			$this->controller->params['pass']
		));
		*/
	}

	/**
	 * undocumented function
	 *
	 * @param string $messages
	 * @return void
	 */
	function missingController($messages = array()) {
		return parent::missingController($messages);
		/*
		$this->controller->redirect(array_merge(
			array('controller' => 'wiki', 'action' => 'index', $this->controller->params['controller']),
			$this->controller->params['pass']
		));
		*/
	}
}
?>