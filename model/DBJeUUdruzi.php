<?php

namespace model;
use app\model\AbstractDBModel;

class DBJeUUdruzi extends AbstractDBModel {

	public function getTable() {
		return 'jeuudruzi';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idJeUUdruzi');
	}
	
	public function getColumns() {
		return array ('idUdruge','idOsobe');
	}
}

?>