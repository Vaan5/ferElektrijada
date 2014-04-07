<?php

namespace model;
use app\model\AbstractDBModel;

class DBPodrucje extends AbstractDBModel {     
    public function getTable(){
        return 'podrucje';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idPodrucja';
    }
            
    public function getColumns(){
        return array ('nazivPodrucja', 'idNadredjenog');
    }
    
    public function getAll() {
	return $this->select()->fetchAll();
    }
}
