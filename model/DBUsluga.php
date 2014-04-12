<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBUsluga extends AbstractDBModel {
  
    public function getTable(){
        return 'usluga';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idUsluge';
    }
            
    public function getColumns(){
        return array('nazivUsluge');
    }
    
    public function getAll() {
	return $this->select()->fetchAll();
    }
}

