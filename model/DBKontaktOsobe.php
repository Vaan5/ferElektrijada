<?php

namespace model;
use app\model\AbstractDBModel;

class DBKontaktOsobe extends AbstractDBModel {

	public function getTable() {
		return 'kontaktosobe';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idKontakta');
	}
	
	public function getColumns() {
		return array ('imeKontakt','prezimeKontakt','telefon','radnoMjesto','idTvrtke','idSponzora');
	}
}

?>