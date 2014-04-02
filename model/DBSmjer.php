
<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBSmjer extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'smjer';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idSmjera';
    }
            
    public function getColumns(){
        return array('nazivSmjera');
    }
}

