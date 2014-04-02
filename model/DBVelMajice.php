<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBVelMajice extends AbstractDBModel {
	    
	/**
    *
	* @var boolean 
	*/
            
    public function getTable(){
        return 'velmajice';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idVelicine';
    }
            
    public function getColumns(){
        return array('velicina');
    }
}

