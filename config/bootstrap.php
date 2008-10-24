<?php
/**
 * Short description for file.
 *
 * Long description for file
 *
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Licensed under The MIT License
 * Redistributions of files must retain the copyright notice.
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.config
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
Configure::write('Content', array(
	'git' => APP . 'content' . DS . 'git' . DS,
	'svn' => APP . 'content' . DS . 'svn' . DS ,
));
?>