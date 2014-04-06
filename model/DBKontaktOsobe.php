<?php

namespace model;
use app\model\AbstractDBModel;

class DBKontaktOsobe extends AbstractDBModel {

	public function getTable() {
		return 'kontaktosobe';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idKontakta');
	}
	
	public function getColumns() {
		return array ('imeKontakt','prezimeKontakt','telefon','radnoMjesto','idTvrtke','idSponzora');
	}
        
        public function addNewContact($imeKontakt, $prezimeKontakt, $telefon, $radnoMjesto, $idTvrtke, $idSponzora) {
            $this->{$this->getPrimaryKeyColumn()} = null;
            $atributi = $this->getColumns();
            foreach($atributi as $a) {
                $this->{$a} = ${$a};
            }
            $this->save();
        }
        
        public function getAll() {
            return $this->select()->fetchAll();
        }
        
        public function deleteRow($idKontakta) {
        try {
            $this->load($idKontakta);
            $this->delete();
        } catch (\app\model\NotFoundException $e) {
            $e = new \PDOException();
            $e->errorInfo[0] = '02000';
            $e->errorInfo[1] = 1604;
            $e->errorInfo[2] = "Zapis ne postoji!";
            throw $e;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}
