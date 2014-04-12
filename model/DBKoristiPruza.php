<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBKoristiPruza extends AbstractDBModel {

    public function getTable(){
        return 'koristipruza';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idKoristiPruza';
    }
            
    public function getColumns(){
        return array ('idUsluge', 'idTvrtke', 'idElektrijade', 'iznosRacuna', 'valutaRacuna', 'nacinPlacanja', 'napomena');
    }
    
    public function getAll() {
	return $this->select()->fetchAll();
    }
    
    public function addRow($idUsluge, $idTvrtke, $idElektrijade, $iznosRacuna, $valutaRacuna, $nacinPlacanja, $napomena) {
	try {
            $atributi = $this->getColumns();
	    foreach ($atributi as $a)
		$this->{$a} = ${$a};
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}