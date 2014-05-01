<?php

namespace model;
use app\model\AbstractDBModel;

class DBBus extends AbstractDBModel {

	public function getTable() {
		return 'bus';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idBusa');
	}
	
	public function getColumns() {
		return array ('registracija','brojMjesta','brojBusa');
	}
}
