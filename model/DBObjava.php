<?php

namespace model;
use app\model\AbstractDBModel;

class DBObjava extends AbstractDBModel {

	public function getTable() {
		return 'objava';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idObjave');
	}
	
	public function getColumns() {
		return array ('datumObjave','link','autorIme','autorPrezime','idMedija','dokument');
	}
}

?>