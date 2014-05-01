<?php

namespace model;
use app\model\AbstractDBModel;

class DBImaatribut extends AbstractDBModel {

    public function getTable() {
        return 'imaatribut';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idImaAtribut';
    }
    
    public function getColumns() {
        return array('idPodrucja', 'idAtributa', 'idSudjelovanja');
	}
	
	public function getVoditelji($idPodrucja, $idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT osoba.* FROM osoba JOIN sudjelovanje
														ON osoba.idOsobe = sudjelovanje.idOsobe
														JOIN imaatribut
														ON imaatribut.idSudjelovanja = sudjelovanje.idSudjelovanja
														JOIN atribut ON UPPER(nazivAtributa) = 'VODITELJ'
										WHERE imaatribut.idPodrucja = :idPodrucja AND sudjelovanje.idElektrijade = :idElektrijade");
			$q->bindValue(":idElektrijade", $idElektrijade);
			$q->bindValue(":idPodrucja", $idPodrucja);
			$q->execute();
			$pov = $q->fetchAll(\PDO::FETCH_CLASS, get_class(new DBOsoba()));
			return $pov;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function addRow($idPodrucja, $idAtributa, $idSudjelovanja) {
		try {
			$atributi = $this->getColumns();
			foreach ($atributi as $a) {
				$this->{$a} = ${$a};
			}
			$this->save();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
}