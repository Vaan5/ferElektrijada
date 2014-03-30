<?php

namespace model;
use app\model\AbstractDBModel;

class DBSudjelovanje extends AbstractDBModel {
    
    /**
     *
     * @var boolean 
     */
    private $isLoggedIn = false;
    
    public function getTable() {
        return 'sudjelovanje';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idSudjelovanja';
    }
    
    public function getColumns() {
        return array('idOsobe', 'idElektrijade', 'tip', 'idVelicine', 'idGodStud', 'idSmjera',
            'idRadnogMjesta', 'idZavoda', 'idPutovanja');
    }
?>