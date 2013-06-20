<?php

/**
 * The Lendio Slim-based API framework. A tool we hope will provide speed in 
 * both framework performance and development time. 
 * @author the lendio dev team developers@lendio.com
 */

/**
 * Set the PHP error reporting level. If you set this in php.ini, you remove this.
 * @see  http://php.net/error_reporting
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In a production environment, it is safe to ignore notices and strict warnings.
 * Disable them by using: E_ALL ^ E_NOTICE
 *
 * When using a legacy application with PHP >= 5.3, it is recommended to disable
 * deprecated notices. Disable with: E_ALL & ~E_DEPRECATED
 */
error_reporting(-1);

// Include the composer autoloader
require 'vendor/autoload.php';

$app = new \Slim\Slim;

\G::instance()->registerNamespace('', 'app/');

$db = \G::createDataSource(
	[
		'name' => 'db',
		'type' => 'mysql',
		'user' => 'employees',
		'password' => '',
		'host' => '127.0.0.1',
		'schema' => 'employees'
	]		
);

\G::instance()->registerDataSource($db);

$app->g = \G::instance();

$app->get('/employee', function() use($app) {
	$employees = $app->g->findAll('Employee', $app->g->criteria()->limit(0,100));

	$app->response()->body($employees->hal());	
});

$app->get('/employee/:id', function($id) use($app) {
	$employee = $app->g->find('Employee', $id);

	$app->response()->body($employee->hal());	
});

$app->run();
