<?php

namespace model;
use app\model\AbstractDBModel;

class DBPodrucjeSudjelovanja extends AbstractDBModel {

	public function getTable() {
		return 'podrucjesudjelovanja';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idPodrucjeSudjelovanja');
	}
	
	public function getColumns() {
		return array ('idPodrucja','idSudjelovanja','rezultatPojeinacni','vrstaPodrucja', 'ukupanBrojSudionika');
	}
}

?>