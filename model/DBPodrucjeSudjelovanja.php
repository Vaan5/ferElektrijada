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
	
	public function alreadyExists($idPodrucja, $idSudjelovanja, $vrstaPodrucja) {
		try {
			if ($vrstaPodrucja !== NULL) {
				$pov = $this->select()->where(array(
					"idPodrucja" => $idPodrucja,
					"idSudjelovanja" => $idSudjelovanja,
					"vrstaPodrucja" => $vrstaPodrucja
				))->fetchAll();
			} else {
				$pov = $this->select()->where(array(
					"idPodrucja" => $idPodrucja,
					"idSudjelovanja" => $idSudjelovanja
				))->fetchAll();
			}
			
			return count($pov) === 0 ? false : true;
		} catch (app\model\NotFoundException $e) {
			return true;
		} catch (\PDOException $e) {
			return true;
		}
	}
	
	public function isParticipating($idSudjelovanja) {
		try {
			$pov = $this->select()->where(array(
				"idSudjelovanja" => $idSudjelovanja
			))->fetchAll();
			
			return count($pov) === 0 ? false : true;
		} catch (app\model\NotFoundException $e) {
			return true;
		} catch (\PDOException $e) {
			return true;
		}
	}
	
	public function modifyRow($idPodrucjeSudjelovanja, $idPodrucja, $idSudjelovanja, 
			$rezultatPojedinacni, $vrstaPodrucja, $ukupanBrojSudionika, $iznosUplate, $valuta = "HRK") {
		try {
			$this->load($idPodrucjeSudjelovanja);
			$atributi = $this->getColumns();
			
			foreach ($atributi as $a) {
				if (${$a} !== FALSE)
					$this->{$a} = ${$a};
			}
			
			$this->save();
		} catch (\app\model\NotFoundException $e) {
			$e = new \PDOException();
			$e->errorInfo[0] = '02000';
			$e->errorInfo[1] = 1604;
			$e->errorInfo[2] = "Ne postoji zapis o natjecanju za traženu osobu!";
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function getAllContestantFields($idSudjelovanja) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM podrucjesudjelovanja JOIN podrucje ON
										podrucje.idPodrucja = podrucjeSudjelovanja.idPodrucja
										WHERE
										podrucjeSudjelovanja.idSudjelovanja = :idSudjelovanja");
			$q->bindValue(":idSudjelovanja", $idSudjelovanja);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function loadIfExists($idPodrucja, $idSudjelovanja, $vrstaPodrucja) {
		try {
			$pov = $this->select()->where(array(
				"idPodrucja" => $idPodrucja,
				"idSudjelovanja" => $idSudjelovanja,
				"vrstaPodrucja" => $vrstaPodrucja
			))->fetchAll();
			
			if(count($pov)) {
				$this->load($pov[0]->idPodrucjeSudjelovanja);
			}
			return $this;
		} catch (\app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function getCollectedMoney($idPodrucja, $idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT podrucjeSudjelovanja.idPodrucja,
												podrucjeSudjelovanja.idSudjelovanja,
												podrucjeSudjelovanja.rezultatPojedinacni,
												podrucjeSudjelovanja.iznosUplate,
												podrucjeSudjelovanja.valuta,
												podrucjeSudjelovanja.idPodrucjeSudjelovanja,
												podrucjeSudjelovanja.vrstaPodrucja,
												osoba.*,
												sudjelovanje.*										
										FROM podrucjeSudjelovanja
											JOIN sudjelovanje ON sudjelovanje.idSudjelovanja = podrucjeSudjelovanja.idSudjelovanja
											JOIN osoba ON osoba.idOsobe = sudjelovanje.idOsobe
										GROUP BY podrucjeSudjelovanja.idSudjelovanja
										HAVING sudjelovanje.idElektrijade = :idElektrijade AND podrucjesudjelovanja.idPodrucja = :idPodrucja
										ORDER BY podrucjeSudjelovanja.vrstaPodrucja ASC");
			$q->bindValue(":idElektrijade", $idElektrijade);
			$q->bindValue(":idPodrucja", $idPodrucja);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function getMoneyStatistics($idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT SUM(podrucjesudjelovanja.iznosUplate) suma,
										podrucjeSudjelovanja.idPodrucja,
										podrucje.nazivPodrucja,
										sudjelovanje.idElektrijade
										FROM podrucjesudjelovanja
											JOIN sudjelovanje ON sudjelovanje.idSudjelovanja = podrucjesudjelovanja.idSudjelovanja
											JOIN podrucje ON podrucje.idPodrucja = podrucjesudjelovanja.idPodrucja
										GROUP BY podrucjesudjelovanja.idPodrucja
										HAVING sudjelovanje.idElektrijade = :idElektrijade
										ORDER BY podrucjesudjelovanja.vrstaPodrucja ASC");
			$q->bindValue(":idElektrijade", $idElektrijade);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function getAllMoney($idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT SUM(podrucjesudjelovanja.iznosUplate) as suma
										FROM podrucjesudjelovanja
										JOIN sudjelovanje ON sudjelovanje.idSudjelovanja = podrucjesudjelovanja.idSudjelovanja
										WHERE sudjelovanje.idElektrijade = :idElektrijade
										ORDER BY podrucjesudjelovanja.vrstaPodrucja ASC");
			$q->bindValue(":idElektrijade", $idElektrijade);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
}
