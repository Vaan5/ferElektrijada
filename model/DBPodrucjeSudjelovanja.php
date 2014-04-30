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
		return array ('idPodrucja','idSudjelovanja','rezultatPojedinacni','vrstaPodrucja', 'ukupanBrojSudionika', 'iznosUplate', 'valuta');
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
	
	public function getPaticipants($idPodrucja, $idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiPopisSvihSudionikaIzPodrucja(:idElektrijade, :idPodrucja)");
			$q->bindValue(":idElektrijade", $idElektrijade);
			$q->bindValue(":idPodrucja", $idPodrucja);
			$q->execute();
			$pov = $q->fetchAll();
			return $pov;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function addRow($idPodrucja, $idSudjelovanja, $rezultatPojedinacni, $vrstaPodrucja, $ukupanBrojSudionika, $iznosUplate, $valuta = "HRK") {
		try {
			$atributi = $this->getColumns();
			foreach ($atributi as $a) {
				$this->{$a} = ${$a};
			}
			$this->save();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
}
