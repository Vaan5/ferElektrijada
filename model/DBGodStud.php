<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBGodStud extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'godstud';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idGodStud';
    }
            
    public function getColumns(){
        return array ('studij', 'godina');
    }
}


