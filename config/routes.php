<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

Router::parseExtensions('rss');

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
	'action' => 'branches|history|branch|logs|view|start|add|edit|modify|delete|remove|activate|forgotten|verify|change|login|account|logout|forks',
	'project' => false)
);
Router::connect('/:controller/*', array(), array(
	'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects',
	'action' => 'index', 'project' => false)
);


/* Genral Fork Routes */
Router::connect('/forks/:fork/:project/', array('controller' => 'source', 'action' => 'index'), array('fork' => '[\-_\.a-zA-Z0-9]{3,}'));
Router::connect('/forks/:fork/:project/:controller', array('action' => 'index'), array('fork' => '[\-_\.a-zA-Z0-9]{3,}', 'action' => 'index'));
Router::connect('/forks/:fork/:project/:controller/:action/*', array(), array(
	'fork' => '[\-_\.a-zA-Z0-9]{3,}',
	'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects|repo',
	'action' => 'branches|history|branch|logs|fast_forward|view|add|edit|modify|delete|remove|parent')
);
Router::connect('/forks/:fork/:project/:controller/*', array(), array(
	'fork' => '[\-_\.a-zA-Z0-9]{3,}',
	'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects',
	'action' => 'index')
);


/* Genral Project Routes */
Router::connect('/:project', array('controller' => 'source', 'action' => 'index'), array('project' => '[_a-zA-Z0-9]{3,}'));
Router::connect('/:project/:controller', array('action' => 'index'), array('project' => '[_a-zA-Z0-9]{3,}', 'action' => 'index'));
Router::connect('/:project/:controller/:action/*', array(), array(
	'project' => '[_a-zA-Z0-9]{3,}',
	'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects|repo',
	'action' => 'branches|history|branch|logs|merge|view|add|edit|modify|delete|remove|forks')
);
Router::connect('/:project/:controller/*', array(), array(
	'project' => '[_a-zA-Z0-9]{3,}',
	'controller' => 'source|wiki|commits|tickets|comments|timeline|versions|users|projects',
	'action' => 'index')
);
