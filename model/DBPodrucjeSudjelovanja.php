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
    
    public function getContestantAreas($idSudjelovanja) {
	try {
	    $pov = $this->select()->where(array(
		"idSudjelovanja" => $idSudjelovanja
	    ))->fetchAll();
	    
	    $p = array();
	    if (count($pov)) {
		foreach ($pov as $v) {
		    $podrucje = new DBPodrucje();
		    $podrucje->load($v->idPodrucja);
		    $p[] = $podrucje;
		}
		return $p;
	    }
	    return array();
	} catch (\app\model\NotFoundException $e) {
	    $e = new \PDOException();
            $e->errorInfo[0] = '02000';
            $e->errorInfo[1] = 1604;
            $e->errorInfo[2] = "Tražena osoba se ne takmiči na aktualnoj Elektrijadi!";
            throw $e;
	} catch (\PDOException $e) {
	    throw $e;
	}
    }
}
