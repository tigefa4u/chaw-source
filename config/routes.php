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
 * @subpackage		chaw.config
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
	Router::connect('/', array('controller' => 'wiki', 'action' => 'index'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/install', array('admin'=> true, 'controller' => 'projects', 'action' => 'add'));

	Router::connect('/commits', array('controller' => 'commits', 'action' => 'index'));
	Router::connect('/commits/:action/*', array('controller' => 'commits', 'action' => 'index'));

	Router::connect('/browser', array('controller' => 'browser', 'action' => 'index'));
	Router::connect('/browser/:action/*', array('controller' => 'browser', 'action' => 'index'));

	Router::connect('/tickets', array('controller' => 'tickets', 'action' => 'index'));
	Router::connect('/tickets/:action/*', array('controller' => 'tickets', 'action' => 'index'));

	Router::connect('/timeline', array('controller' => 'timeline', 'action' => 'index'));
	Router::connect('/timeline/:action/*', array('controller' => 'timeline', 'action' => 'index'));

	Router::connect('/versions', array('controller' => 'versions', 'action' => 'index'));
	Router::connect('/versions/:action/*', array('controller' => 'versions', 'action' => 'index'));

	Router::connect('/users', array('controller' => 'users', 'action' => 'index'));
	Router::connect('/users/:action/*', array('controller' => 'users', 'action' => 'index'));

	Router::connect('/wiki/edit/:id', array('controller' => 'wiki', 'action' => 'add'), array('pass' => array('id')));
	Router::connect('/wiki/add/*', array('controller' => 'wiki', 'action' => 'add'));
	Router::connect('/wiki/*', array('controller' => 'wiki', 'action' => 'index'));
	Router::connect('/wiki/*', array('controller' => 'wiki', 'action' => 'view'));


	Router::connect('/projects', array('controller' => 'projects', 'action' => 'index'));
	Router::connect('/projects/view/:project', array('controller' => 'projects', 'action' => 'view'));
	Router::connect('/projects/:action/*', array('controller' => 'projects', 'action' => $Action));

	Router::connect('/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'));
	Router::connect('/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'));

	Router::connect('/:project/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'));
	Router::connect('/:project/admin/:controller/:action/:id', array('admin'=> true, 'controller' => 'dashboard'));
	Router::connect('/:project/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'));

	Router::connect('/:project/wiki/edit/:id', array('controller' => 'wiki', 'action' => 'add'), array('pass' => array('id')));
	Router::connect('/:project/wiki/add/*', array('controller' => 'wiki', 'action' => 'add'));
	Router::connect('/:project/wiki/*', array('controller' => 'wiki', 'action' => 'index'));

	Router::connect('/:project/:controller', array('action' => 'index'), array('action' => 'index'));
	//Router::connect('/:project/:controller/:action/:id', array(), array('action' => 'view|edit|modify|delete', 'id' => $ID, 'pass' => array('id')));
	Router::connect('/:project/:controller/:action/*', array(), array('action' => 'index|view|add|edit|modify|delete'));
?>