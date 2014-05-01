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
	
	public function loadIfExists($primaryKey) {
		try {
			$this->load($primaryKey);
		} catch (\app\model\NotFoundException $e) {
			return;
		} catch (\PDOException $e) {
			return;
		}
    }
}
