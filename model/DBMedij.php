<?php

namespace model;
use app\model\AbstractDBModel;

class DBMedij extends AbstractDBModel {

    public function getTable() {
	return 'medij';
    }

    public function getPrimaryKeyColumn() {
	return ('idMedija');
    }

    public function getColumns() {
	return array ('nazivMedija');
    }

    public function getAll() {
	return $this->select()->fetchAll();
    }
}