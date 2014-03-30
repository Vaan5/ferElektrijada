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
		return array ('idKontakta','email');
	}
}

?>