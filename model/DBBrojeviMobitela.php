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
		return ('broj');
	}
}

?>