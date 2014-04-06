<?php

namespace model;
use app\model\AbstractDBModel;

class DBBrojeviMobitela extends AbstractDBModel {

	public function getTable() {
		return 'brojevimobitela';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idBroja');
	}
	
	public function getColumns() {
		return array('idKontakta', 'broj');
	}
        
        /**
         * Adds a new number only if there isn't already a number like that in the db
         */
        public function addNewOrIgnore($idKontakta, $broj) {
            try {
                $this->{$this->getPrimaryKeyColumn()} = null;
                $this->broj = $broj;
                $this->idKontakta = $idKontakta;
                $this->save();
            } catch (\PDOException $e) {
                return;
            }
        }
}
