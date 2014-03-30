<?php

namespace model;
use app\model\AbstractDBModel;

class DBAtribut extends AbstractDBModel {
    
    /**
     *
     * @var boolean 
     */
    private $isLoggedIn = false;
    
    public function getTable() {
        return 'atribut';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idAtributa';
    }
    
    public function getColumns() {
        return array('nazivAtributa');
    }
?>