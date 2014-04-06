<?php

namespace model;
use app\model\AbstractDBModel;

class DBEmailAdrese extends AbstractDBModel {

	public function getTable() {
		return 'emailadrese';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idAdrese');
	}
	
	public function getColumns() {
		return array('idKontakta','email');
	}
        
        /**
         * Adds a new email only if there isn't already an e-mail like that in the db
         */
        public function addNewOrIgnore($idKontakta, $email) {
            try {
                $this->{$this->getPrimaryKeyColumn()} = null;
                $this->email = $email;
                $this->idKontakta = $idKontakta;
                $this->save();
            } catch (\PDOException $e) {
                return;
            }
        }
}
