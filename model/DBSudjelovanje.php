<?php

namespace model;
use app\model\AbstractDBModel;

class DBSudjelovanje extends AbstractDBModel {
    
    public function getTable() {
        return 'sudjelovanje';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idSudjelovanja';
    }
    
    public function getColumns() {
        return array('idOsobe', 'idElektrijade', 'tip', 'idVelicine', 'idGodStud', 'idSmjera',
            'idRadnogMjesta', 'idZavoda', 'idPutovanja');
    }
    
    /**************************************************************************
     *			   CONTESTANT FUNCTIONS
     **************************************************************************/
	
    public function isActiveContestant($id) {
		$elektrijada = new DBElektrijada();
		$idElektrijade = $elektrijada->getCurrentElektrijadaId();

		try {
			$pov = $this->select()->where(array(
				"idElektrijade" => $idElektrijade,
				"idOsobe" => $id
			))->fetchAll();
			if (count($pov))
				return true;
			return false;
		} catch (\PDOException $e) {
			return false;
		}
		return false;
    }
    
    public function getContestantAreas($idOsobe, $idElektrijade) {
		try {
			$pov = $this->select()->where(array(
				"idOsobe" => $idOsobe,
				"idElektrijade" => $idElektrijade
			))->fetchAll();

			if (count($pov)) {
				$podrucje = new DBPodrucje();
				$povratnaVrijednost = array();
				foreach($pov as $v) {
					$podrucjeSudjelovanja = new DBPodrucjeSudjelovanja();
					$p = $podrucjeSudjelovanja->getContestantAreas($v->getPrimaryKey());
					foreach ($p as $l) {
						$povratnaVrijednost[] = $l;
					}
				}

				return $povratnaVrijednost;
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
    
    public function loadByContestant($idOsobe, $idElektrijade) {
		try {
			$pov = $this->select()->where(array(
				"idOsobe" => $idOsobe,
				"idElektrijade" => $idElektrijade
			))->fetchAll();

			if (count($pov))
				$this->load($pov[0]->getPrimaryKey());
			else
				$this->{$this->getPrimaryKeyColumn()} = null;
		} catch (\PDOException $e) {
			throw $e;
		}
    }
    
    public function isStudent() {
		return $this->tip === 'S' ? true : false;
    }
    
    public function isStaff() {
		return $this->tip === 'D' ? true : ($this->tip === 'O' ? true : false);
    }
	
	/**
	 * Modifies row with the given primaryKey
	 * Attributes with FALSE value wont be changed, 
	 * Attributes with NULL value will
	 */
	public function modifyRow($idSudjelovanja, $idOsobe, $idElektrijade, $tip, $idVelicine, $idGodStud, $idSmjera,
            $idRadnogMjesta, $idZavoda, $idPutovanja) {
		try {
			$this->load($idSudjelovanja);
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
			$e->errorInfo[2] = "Ne postoji zapis o sudjelovanju za traženu osobu!";
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
		
	}
	
	/**************************************************************************
     *			   TEAM LEADER FUNCTIONS
     **************************************************************************/
	
	public function addRow($idOsobe, $idElektrijade, $tip, $idVelicine, $idGodStud, $idSmjera,
            $idRadnogMjesta, $idZavoda, $idPutovanja) {
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
	
	/**************************************************************************
     *			   OZSN FUNCTIONS
     **************************************************************************/
	public function exists($idOsobe, $idElektrijade) {
		try {
			$pov = $this->select()->where(array(
				"idOsobe" => $idOsobe,
				"idElektrijade" => $idElektrijade
			))->fetchAll();
			return count($pov) == 0 ? false : $pov[0]->getPrimaryKey();
		} catch (app\model\NotFoundException $e) {
			return false;
		} catch (\PDOException $e) {
			return false;
		}
	}
}