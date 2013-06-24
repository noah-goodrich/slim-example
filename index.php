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

$app->add(new \Slim\Middleware\ContentTypes);
$app->add(new \Middleware\Accept);

$app->options('/', function() use($app) {
	$app->response()->body('success');
});

$app->get('/', function() use($app) {
	$app->response()->body('hello');
});

$app->get('/employee', function() use($app) {
	$employees = $app->g->findAll('Employee', $app->g->criteria()->limit(0,100));
	
	$hal = $employees->hal();
	$link = new \Hal\Link('/employee/{id}', 'employee', null, null, null, true);
	$hal->setLink($link);	
	
	$app->response()->hal = $hal;
});

$app->get('/employee/:id', function($id) use($app) {
	$employee = $app->g->find('Employee', $id);
	
	$app->response()->hal = $employee->hal();
});

$app->post('/employee', function() use($app) {
	$data = $app->request()->getBody();
	
	$employee = new \Model\Employee('\Mapper\Employee');
		
	$employee->setData($data);

	if($employee->save()) {
		$app->response()->status(201);
		$app->response()->hal = $employee->hal();
	} else {
		$app->response()->status(500);
		$app->response()->hal = new \Hal\Resource('/error', ['errors' => $employee->errors]);
	}
});

$app->delete('/employee/:id', function($id) use($app) {
	$employee = $app->g->find('Employee', $id);

	if($employee->delete()) {
		$app->response()->status(200);
	} else {
		$app->response()->status(500);
	}
});
$app->run();
