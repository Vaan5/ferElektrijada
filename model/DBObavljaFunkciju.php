<?php

namespace model;
use app\model\AbstractDBModel;

class DBObavljaFunkciju extends AbstractDBModel {

	public function getTable() {
		return 'obavljafunkciju';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idObavljaFunkciju');
	}
	
	public function getColumns() {
		return array ('idOsobe','idFunkcije','idElektrijade');
	}
        
        public function addNewRow($idOsobe, $idFunkcije, $idElektrijade) {
            $this->{$this->getPrimaryKeyColumn()} = null;
            $atributi = $this->getColumns();
            foreach($atributi as $a) {
                $this->{$a} = ${$a};
            }
            $this->save();
        }
}