<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBSponzor extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'sponzor';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idSponzora';
    }
            
    public function getColumns(){
        return array ('imeTvrtke', 'adresaTvrtke');
    }
}


