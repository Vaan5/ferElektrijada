<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBZavod extends AbstractDBModel {
	    
	/**
    *
	* @var boolean 
	*/
            
    public function getTable(){
		return 'zavod';
    }
            
    public function getPrimaryKeyColumn(){
		return 'idZavoda';
    }
            
    public function getColumns(){
		return array('nazivZavoda','skraceniNaziv');
    }
}

