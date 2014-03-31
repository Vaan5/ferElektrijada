<?php

namespace model;
use app\model\AbstractDBModel;

class DBPutovanje extends AbstractDBModel {

	public function getTable() {
		return 'putovanje';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idPutovanja');
	}
	
	public function getColumns() {
		return array ('idBusa','polazak','povratak','napomena','brojSjedala');
	}
}

?>

