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
	Router::parseExtensions('rss', 'tar');

	Router::connect('/', array('controller' => 'wiki', 'action' => 'index'));

	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/start', array('controller' => 'pages', 'action' => 'start'));

	Router::connect('/dashboard', array('controller' => 'dashboard', 'action' => 'index'));

	Router::connect('/download/:project', array('controller' => 'projects', 'action' => 'index'));

	Router::connect('/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'), array('admin' => true));
	Router::connect('/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'), array('admin' => true));


	Router::connect('/:controller/:action/*', array(), array(
		'controller' => 'commits|tickets|timeline|versions|users|projects',
		'action' => 'history|view|add|edit|modify|delete')
	);

	Router::connect('/:controller/*', array(), array(
		'controller' => 'commits|tickets|timeline|versions|users|projects',
		'action' => 'index')
	);

	Router::connect('/browser/*', array('controller' => 'browser', 'action' => 'index'));
	Router::connect('/wiki/edit/:id', array('controller' => 'wiki', 'action' => 'add'), array('pass' => array('id')));
	Router::connect('/wiki/add/*', array('controller' => 'wiki', 'action' => 'add'));
	Router::connect('/wiki/*', array('controller' => 'wiki', 'action' => 'index'));


	//Fork routes
	Router::connect('/forks/:fork/:project/', array('controller' => 'browser', 'action' => 'index'));

	Router::connect('/:project/fork', array('controller' => 'projects', 'action' => 'fork'), array('action' => 'fork'));
	Router::connect('/download/forks/:fork/:project', array('controller' => 'projects', 'action' => 'index'));

	Router::connect('/forks/:fork/:project/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'));
	//Router::connect('/:project/forks/:fork/admin/:controller/:action/:id', array('admin'=> true, 'controller' => 'dashboard'));
	Router::connect('/forks/:fork/:project/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'));

	Router::connect('/forks/:fork/:project/wiki/edit/:id', array('controller' => 'wiki', 'action' => 'add'), array('pass' => array('id')));
	Router::connect('/forks/:fork/:project/wiki/add/*', array('controller' => 'wiki', 'action' => 'add'));
	Router::connect('/forks/:fork/:project/wiki/*', array('controller' => 'wiki', 'action' => 'index'));

	Router::connect('/forks/:fork/:project/browser/*', array('controller' => 'browser', 'action' => 'index'));

	Router::connect('/forks/:fork/:project/:controller', array('action' => 'index'), array('action' => 'index'));

	Router::connect('/forks/:fork/:project/:controller/:action/*', array(), array(
		'controller' => 'commits|tickets|timeline|versions|users|projects',
		'action' => 'history|view|add|edit|modify|delete')
	);

	Router::connect('/forks/:fork/:project/:controller/*', array(), array(
		'controller' => 'commits|tickets|timeline|versions|users|projects',
		'action' => 'index')
	);


	//Base project routes
	Router::connect('/:project', array('controller' => 'browser', 'action' => 'index'));

	Router::connect('/:project/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'));
	//Router::connect('/:project/admin/:controller/:action/:id', array('admin'=> true, 'controller' => 'dashboard'));
	Router::connect('/:project/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'));

	Router::connect('/:project/wiki/edit/:id', array('controller' => 'wiki', 'action' => 'add'), array('pass' => array('id')));
	Router::connect('/:project/wiki/add/*', array('controller' => 'wiki', 'action' => 'add'));
	Router::connect('/:project/wiki/*', array('controller' => 'wiki', 'action' => 'index'));

	Router::connect('/:project/browser/*', array('controller' => 'browser', 'action' => 'index'));

	//these should be last

	Router::connect('/:project/:controller', array('action' => 'index'), array('action' => 'index'));

	Router::connect('/:project/:controller/:action/*', array(), array(
		'controller' => 'commits|tickets|timeline|versions|users|projects',
		'action' => 'history|view|add|edit|modify|delete')
	);

	Router::connect('/:project/:controller/*', array(), array(
		'controller' => 'commits|tickets|timeline|versions|users|projects',
		'action' => 'index')
	);



?>