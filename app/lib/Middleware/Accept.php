<?php

namespace Middleware;

class Accept extends \Slim\Middleware {
	
	public function call()
	{
		$this->next->call();	

		if(isset($this->app->request()->headers()['ACCEPT']) && $this->app->request()->headers()['ACCEPT'] == 'application/hal+xml') {
			$this->app->response()->body($this->app->response()->hal->getXml()->asXml());
		} elseif(property_exists($this->app->response(), 'hal')) {
			$this->app->response()->body($this->app->response()->hal);
		}
	}
}
