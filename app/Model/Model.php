<?php

namespace Model;

use Hal\Resource, 
    Hal\Link;

abstract class Model extends \Gacela\Model\Model {

	protected $_endpoint;
	
	protected $_endpointIdField = 'id';

    public function hal($include = array('_links', '_data', '_embedded'))
	{
		if(!is_array($include)) {
			$include = array();
		}
		
		if(!$this->_endpoint) {
			$end = explode("\\", get_class($this));
			$this->_endpoint = strtolower(end($end));
		}
		
		$resource = new Resource($this->_endpoint.'/'.$this->{$this->_endpointIdField});
		
		if(in_array('_data', $include)) {
			$resource->setData($this->_data());
		}
		
		if(in_array('_embedded', $include)) {
			foreach($this->_embedded() as $m) {
				$resource->setEmbedded($m, $this->$m->hal(null));
			}
		}
		
		if(in_array('_links', $include)) {
			foreach($this->_links() as $link) {
				$resource->setLink($link);				
			}
		}

		return $resource;
    }

    protected function _data()
    {
	   return (array) $this->_data;
    }

	protected function _embedded()
	{
		return array();
	}

    protected function _links()
    {
        return array();
    }
}
