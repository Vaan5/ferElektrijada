<?php

namespace model;
use app\model\AbstractDBModel;

class DBSponElekPod extends AbstractDBModel {     
    
    // dodaj one prve tri metode
    
    public function getAll() {
	return $this->select()->fetchAll();
    }
}
