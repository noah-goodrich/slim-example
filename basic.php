<?php

/**
 * The Lendio Slim-based API framework. A tool we hope will provide speed in 
 * both framework performance and development time. 
 * @author the lendio dev team developers@lendio.com
 */


// Include the composer autoloader
require 'vendor/autoload.php';

$app = new \Slim\Slim;

$app->get('/', function() use($app) {
	$app->response()->body("hello world");
});

$app->run();
