<?php
/* SVN FILE: $Id: routes.php 6296 2008-01-01 22:18:17Z phpnut $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app.config
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 6296 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-01 14:18:17 -0800 (Tue, 01 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.thtml)...
 */
	//Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));

	Router::connect('/', array('controller' => 'projects', 'action' => 'index'));
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

	Router::connect('/projects/:project', array('controller' => 'projects', 'action' => 'view'));
	Router::connect('/projects/:action/*', array('controller' => 'projects', 'action' => $Action));

	Router::connect('/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'));
	Router::connect('/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'));

	Router::connect('/:project/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'));
	Router::connect('/:project/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'));

	Router::connect('/:project/wiki/edit/:id', array('controller' => 'wiki', 'action' => 'add'), array('pass' => array('id')));
	Router::connect('/:project/wiki/add/*', array('controller' => 'wiki', 'action' => 'add'));
	Router::connect('/:project/wiki/*', array('controller' => 'wiki', 'action' => 'index'));

	Router::connect('/:project/:controller', array('action' => 'index'), array('action' => 'index'));
	//Router::connect('/:project/:controller/:action/:id', array(), array('action' => 'view|edit|modify|delete', 'id' => $ID, 'pass' => array('id')));
	Router::connect('/:project/:controller/:action/*', array(), array('action' => 'index|view|add|edit|modify|delete'));
	

?>