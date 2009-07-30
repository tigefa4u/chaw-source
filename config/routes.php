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
 * @subpackage		chaw.config
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
	Router::parseExtensions('rss', 'tar');

	/* Base Routes */
	Router::connect('/', array('controller' => 'wiki', 'action' => 'index'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/start', array('controller' => 'pages', 'action' => 'start'));
	Router::connect('/dashboard', array('controller' => 'dashboard', 'action' => 'index'));
	Router::connect('/feed', array('controller' => 'dashboard', 'action' => 'feed'));

	/* Admin Routes */
	Router::connect('/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'), array('admin' => true));
	Router::connect('/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'), array('admin' => true));

	Router::connect('/forks/:fork/:project/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'));
	Router::connect('/forks/:fork/:project/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'));

	Router::connect('/:project/admin/:controller', array('admin'=> true, 'controller' => 'dashboard'));
	Router::connect('/:project/admin/:controller/:action/*', array('admin'=> true, 'controller' => 'dashboard'));

	/* Specific Routes */
	Router::connect('/fork/it', array('controller' => 'repo', 'action' => 'fork_it'), array('action' => 'fork_it'));
	Router::connect('/:project/fork/it', array('controller' => 'repo', 'action' => 'fork_it'), array('action' => 'fork_it'));
	//Router::connect('/download/:project', array('controller' => 'repo', 'action' => 'download', 'ext' => 'tar'), array('ext' => 'tar'));
	//Router::connect('/download/forks/:fork/:project', array('controller' => 'repo', 'action' => 'download', 'ext' => 'tar'), array('ext' => 'tar'));

	/* General Routes */
	Router::connect('/:controller', array(), array(
		'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects',
		'action' => 'index', 'project' => false)
	);
	Router::connect('/:controller/:action/*', array(), array(
		'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects',
		'action' => 'branches|branch|logs|view|start|add|edit|modify|delete|remove|activate|forgotten|verify|change|login|account|logout|forks',
		'project' => false)
	);
	Router::connect('/:controller/*', array(), array(
		'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects',
		'action' => 'index', 'project' => false)
	);


	/* Genral Fork Routes */
	Router::connect('/forks/:fork/:project/', array('controller' => 'source', 'action' => 'index'));
	Router::connect('/forks/:fork/:project/:controller', array('action' => 'index'), array('action' => 'index'));
	Router::connect('/forks/:fork/:project/:controller/:action/*', array(), array(
		'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects|repo',
		'action' => 'branches|branch|logs|fast_forward|view|add|edit|modify|delete|remove|parent')
	);
	Router::connect('/forks/:fork/:project/:controller/*', array(), array(
		'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects',
		'action' => 'index')
	);


	/* Genral Project Routes */
	Router::connect('/:project', array('controller' => 'source', 'action' => 'index'));
	Router::connect('/:project/:controller', array('action' => 'index'), array('action' => 'index'));
	Router::connect('/:project/:controller/:action/*', array(), array(
		'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects|repo',
		'action' => 'branches|branch|logs|merge|view|add|edit|modify|delete|remove|forks')
	);
	Router::connect('/:project/:controller/*', array(), array(
		'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects',
		'action' => 'index')
	);
