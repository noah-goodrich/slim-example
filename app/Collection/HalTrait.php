<?php 

namespace Collection;

use \Hal\Resource,
	\Hal\Link;

trait HalTrait {
	
	public function hal()
	{
		$endpoint = explode("\\", get_class($this->current()));
		$endpoint = strtolower(end($endpoint));
		
		$resource = new Resource('/'.$endpoint);
		
		$embeds = array();
		foreach($this as $m) {
			$resource->setEmbedded($endpoint, $m->hal(array('_data', '_links')));
		}

		return $resource;
	}
}
