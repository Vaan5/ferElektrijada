<?php

namespace model;
use app\model\AbstractDBModel;

class DBObavljaFunkciju extends AbstractDBModel {

    public function getTable() {
	    return 'obavljafunkciju';
    }

    public function getPrimaryKeyColumn() {
	    return ('idObavljaFunkciju');
    }

    public function getColumns() {
	    return array ('idOsobe','idFunkcije','idElektrijade');
    }
    
    public function deleteRow($id) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiFunkciju(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
	public function deleteIfNotLast($id) {
		try {
			$pdo = $this->getPdo();
			$q1 = $pdo->prepare("SELECT * FROM obavljafunkciju WHERE idObavljaFunkciju = :id");
			$q1->bindValue(":id", $id);
			$q1->execute();
			$pov = $q1->fetchAll(\PDO::FETCH_CLASS, get_class($this));
			if (count($pov)) {
				$q2 = $pdo->prepare("SELECT * FROM obavljafunkciju WHERE idOsobe = :idOsobe AND idElektrijade = :idElektrijade");
				$q2->bindValue(":idOsobe", $pov[0]->idOsobe);
				$q2->bindValue(":idElektrijade", $pov[0]->idElektrijade);
				$q2->execute();
				$brojac = $q2->fetchAll(\PDO::FETCH_CLASS, get_class($this));
				if (count($brojac) !== 1) {
					$q = $pdo->prepare("CALL brisiFunkciju(:id)");
					$q->bindValue(":id", $id);
					$q->execute();
				} else {
					// just delete function name
					$q = $pdo->prepare("UPDATE obavljafunkciju SET idFunkcije = NULL WHERE idObavljaFunkciju = :id");
					$q->bindValue(":id", $id);
					$q->execute();
				}
			}
        } catch (\PDOException $e) {
            throw $e;
        }
	}
    
    public function checkOzsnFunction($primaryKey, $idOsobe) {
		try {
			$this->load($primaryKey);
			return $this->idOsobe == $idOsobe ? true : false;
		} catch (\app\model\NotFoundException $e) {
			$e = new \PDOException();
			$e->errorInfo[0] = '02000';
			$e->errorInfo[1] = 1604;
			$e->errorInfo[2] = "Zapis ne postoji!";
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
    }

	public function addNewRow($idOsobe, $idFunkcije, $idElektrijade) {
		try {
			$this->addOrOverwrite($idOsobe, $idFunkcije, $idElektrijade);
		} catch (app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
    }

    public function deleteRows($idOsobe, $idElektrijade) {
		try {
			$retci = $this->select()->where(array(
				"idOsobe" => $idOsobe,
				"idElektrijade" => $idElektrijade
			))->fetchAll();
			if (count($retci)) {
				foreach($retci as $r) {
					$r->delete();
				}
			}
		} catch (app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
    }

    public function ozsnExists($idOsobe, $idElektrijade) {
		try {
			$pov = $this->select()->where(array("idOsobe" => $idOsobe, "idElektrijade" => $idElektrijade))->fetchAll();
			if(count($pov))
				return true;
			return false;
		} catch (app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
    }
	
    public function loadOzsnFunctions($idOsobe, $idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM funkcija JOIN obavljafunkciju ON obavljafunkciju.idFunkcije = funkcija.idFunkcije
										WHERE obavljafunkciju.idOsobe = :id AND obavljafunkciju.idElektrijade = :idE
										AND obavljafunkciju.idFunkcije IS NOT NULL");
			$q->bindValue(":id", $idOsobe);
			$q->bindValue(":idE", $idElektrijade);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
    }
	
	public function addOrOverwrite($idOsobe, $idFunkcije, $idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT idObavljaFunkciju FROM obavljafunkciju WHERE
									idOsobe = :idOsobe AND idElektrijade = :idELektrijade AND idFunkcije IS NULL");
			$q->bindValue(":idELektrijade", $idElektrijade);
			$q->bindValue(":idOsobe", $idOsobe);
			$q->execute();
			$pov = $q->fetchAll();
			
			if (count($pov)) {
				$this->load($pov[0]->idObavljaFunkciju);
				$this->idFunkcije = $idFunkcije;
				$this->save();
			} else {
				$this->idObavljaFunkciju = null;
				$this->idElektrijade = $idElektrijade;
				$this->idFunkcije = $idFunkcije;
				$this->idOsobe = $idOsobe;
				$this->save();
			}
		} catch (app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
}