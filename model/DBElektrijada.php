<?php

namespace model;
use app\model\AbstractDBModel;

class DBElektrijada extends AbstractDBModel {
    
    public function getTable() {
        return 'elektrijada';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idElektrijade';
    }
    
    public function getColumns() {
        return array('mjestoOdrzavanja', 'datumPocetka', 'datumKraja', 'ukupniRezultat', 'drzava');
    }
    
    public function addNewElektrijada($mjestoOdrzavanja, $datumPocetka, $datumKraja, $ukupniRezultat, $drzava) {
        $this->idElektrijade = null;
        $atributi = $this->getColumns();
        foreach($atributi as $a) {
            $this->{$a} = ${$a};
        }
        $this->save();
    }
}
 