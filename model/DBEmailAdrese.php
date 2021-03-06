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
	
	public function deleteByContact($idKontakta) {
	    try {
		$pov = $this->select()->where(array(
		    "idKontakta" => $idKontakta
		))->fetchAll();
		if (count($pov)) {
		    foreach ($pov as $v) {
			$v->delete();
		    }
		}
	    } catch (\PDOException $e) {
		throw $e;
	    }
	}
	
	public function getContactEmails($idKontakta) {
	    return $this->select()->where(array(
		"idKontakta" => $idKontakta
	    ))->fetchAll();
	}
}
