<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBFunkcija extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'funkcija';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idFunkcije';
    }
            
    public function getColumns(){
        return array('nazivFunkcije');
    }
}

