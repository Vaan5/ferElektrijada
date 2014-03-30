<?php

namespace model;
use app\model\AbstractDBModel;

class DBImaatribut extends AbstractDBModel {
    
    /**
     *
     * @var boolean 
     */
    private $isLoggedIn = false;
    
    public function getTable() {
        return 'imaatribut';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idImaAtribut';
    }
    
    public function getColumns() {
        return array('idPodrucja', 'idAtributa', 'idSudjelovanja');
	}
?>