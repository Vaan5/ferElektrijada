<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBSponzor extends AbstractDBModel {
 
    public function getTable(){
        return 'sponzor';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idSponzora';
    }

    public function getColumns(){
        return array ('imeTvrtke', 'adresaTvrtke', 'logotip');
    }
    
    public function getAll() {
        return $this->select()->fetchAll();
    }
    
    public function getAllByElektrijada($idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM sponzor
								JOIN sponelekpod ON sponelekpod.idSponzora = sponzor.idSponzora
								JOIN podrucje ON podrucje.idPodrucja = sponelekpod.idPodrucja
								WHERE sponelekpod.idElektrijade = :id");
			$q->bindValue(":id", $idElektrijade);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
    }
	
	public function getAllNotDisciplineByElektrijada($idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM sponzor JOIN imasponzora ON sponzor.idSponzora = imasponzora.idSponzora
								 JOIN kategorija ON kategorija.idKategorijeSponzora = imasponzora.idKategorijeSponzora
								 JOIN nacinpromocije ON nacinpromocije.idPromocije = imasponzora.idPromocije
								WHERE imasponzora.idElektrijade = :id");
			$q->bindValue(":id", $idElektrijade);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
    }
    
    public function getAllActive($idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiPopisSvihSponzora(:id)");
			$q->bindValue(":id", $idElektrijade);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
    }
    
    public function getAllArea($idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM sponzor JOIN sponelekpod ON sponzor.idSponzora = sponelekpod.idSponzora
						JOIN podrucje ON sponelekpod.idPodrucja = podrucje.idPodrucja WHERE sponelekpod.idElektrijade = :id
						ORDER BY sponzor.imeTvrtke ASC");
			$q->bindValue(":id", $idElektrijade);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
    }

    public function addRow($imeTvrtke, $adresaTvrtke, $logotip = NULL) {
		try {
            $this->imeTvrtke = $imeTvrtke;
			$this->adresaTvrtke = $adresaTvrtke;
			$this->logotip = $logotip;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function deleteRow($idSponzora) {
		try {
            $this->load($idSponzora);
			if ($this->logotip != null) {
				// delete logo image
				$p = unlink($this->logotip);
				if ($p === false) {
					$e = new \PDOException();
					$e->errorInfo[0] = '02000';
					$e->errorInfo[1] = 1604;
					$e->errorInfo[2] = "Greška prilikom brisanja logotipa!";
					$this->delete();
					throw $e;
				}
			}
			$this->delete();
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
    
    /**
     * Modifies row, but can't delete logotype with this function (use addLogo for that)
     */
    public function modifyRow($idSponzora, $imeTvrtke, $adresaTvrtke, $logotip) {
		try {
			$this->load($idSponzora);
			$this->imeTvrtke = $imeTvrtke;
			$this->adresaTvrtke = $adresaTvrtke;
			if ($logotip !== NULL && $logotip !== '' && $logotip !== false)
				$this->logotip = $logotip;
			$this->save();
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
    
    public function addLogo($idSponzora, $logotip) {
		try {
			$this->load($idSponzora);
			$this->logotip = $logotip;
			$this->save();
		} catch (app\model\NotFoundException $e) {
			$e = new \PDOException();
			$e->errorInfo[0] = '02000';
			$e->errorInfo[1] = 1604;
			$e->errorInfo[2] = "Zapis ne postoji!";
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
    }
}