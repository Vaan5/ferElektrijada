<?php

namespace model;
use app\model\AbstractDBModel;

class DBObjavaOElektrijadi extends AbstractDBModel {

	public function getTable() {
		return 'objavaoelektrijadi';
	}
	
	public function getPrimaryKeyColumn(){
		return 'idObjavaOElektrijadi';
	}
	
	public function getColumns() {
		return array ('idObjave','idElektrijade');
	}
}

?>