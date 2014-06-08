<?php

namespace model;
use app\model\AbstractDBModel;

class DBImaatribut extends AbstractDBModel {

    public function getTable() {
        return 'imaatribut';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idImaAtribut';
    }
    
    public function getColumns() {
        return array('idPodrucja', 'idAtributa', 'idSudjelovanja');
	}
	
	public function getVoditelji($idPodrucja, $idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT osoba.* FROM osoba JOIN sudjelovanje
														ON osoba.idOsobe = sudjelovanje.idOsobe
														JOIN imaatribut
														ON imaatribut.idSudjelovanja = sudjelovanje.idSudjelovanja
														JOIN atribut ON imaatribut.idAtributa = atribut.idAtributa
										WHERE imaatribut.idPodrucja = :idPodrucja AND sudjelovanje.idElektrijade = :idElektrijade AND UPPER(nazivAtributa) = 'VODITELJ'");
			$q->bindValue(":idElektrijade", $idElektrijade);
			$q->bindValue(":idPodrucja", $idPodrucja);
			$q->execute();
			$pov = $q->fetchAll(\PDO::FETCH_CLASS, get_class(new DBOsoba()));
			return $pov;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function addRow($idPodrucja, $idAtributa, $idSudjelovanja) {
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
	
	public function isTeamLeader($idOsobe, $idPodrucja, $idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM osoba JOIN sudjelovanje
														ON osoba.idOsobe = sudjelovanje.idOsobe
														JOIN imaatribut
														ON imaatribut.idSudjelovanja = sudjelovanje.idSudjelovanja
														JOIN atribut ON UPPER(nazivAtributa) = 'VODITELJ'
										WHERE imaatribut.idPodrucja = :idPodrucja AND sudjelovanje.idElektrijade = :idElektrijade
										AND osoba.idOsobe = :idOsobe");
			$q->bindValue(":idElektrijade", $idElektrijade);
			$q->bindValue(":idPodrucja", $idPodrucja);
			$q->bindValue(":idOsobe", $idOsobe);
			$q->execute();
			$pov = $q->fetchAll(\PDO::FETCH_CLASS, get_class(new DBOsoba()));
			return count($pov) == 0 ? false : true;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function hasARole($idSudjelovanja) {
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
	
	public function getAllContestantAttributes($idSudjelovanja) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM imaatribut JOIN podrucje ON
										podrucje.idPodrucja = imaatribut.idPodrucja
										JOIN atribut ON 
										imaatribut.idAtributa = atribut.idAtributa
										WHERE
										imaatribut.idSudjelovanja = :idSudjelovanja");
			$q->bindValue(":idSudjelovanja", $idSudjelovanja);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function deleteContestantsAttributes($idSudjelovanja, $idPodrucja) {
		try {
			$pov = $this->select()->where(array(
				"idSudjelovanja" => $idSudjelovanja,
				"idPodrucja" => $idPodrucja
			))->fetchAll();
			
			if (count($pov)) {
				foreach ($pov as $p) {
					$p->delete();
				}
			}
		} catch (app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function deleteContestantsAttributesExceptLeader($idSudjelovanja, $idPodrucja) {
		try {
			$pov = $this->select()->where(array(
				"idSudjelovanja" => $idSudjelovanja,
				"idPodrucja" => $idPodrucja
			))->fetchAll();
			
			$atribut = new DBAtribut();
			$idVoditelja = $atribut->getTeamLeaderId();
			
			if (count($pov)) {
				foreach ($pov as $p) {
					if ($p->idAtributa != $idVoditelja)
						$p->delete();
				}
			}
		} catch (app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function loadIfExists($idPodrucja, $idSudjelovanja) {
		try {
			$pov = $this->select()->where(array(
				"idPodrucja" => $idPodrucja,
				"idSudjelovanja" => $idSudjelovanja
			))->fetchAll();
			
			if(count($pov)) {
				$this->load($pov[0]->idImaAtribut);
			}
			return $this;
		} catch (\app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	} 
}