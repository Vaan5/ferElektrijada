<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBElekPodrucje extends AbstractDBModel {
      
    public function getTable(){
        return 'elekpodrucje';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idElekPodrucje';
    }
 
    public function getColumns(){
        return array ('idPodrucja', 'rezultatGrupni', 'slikaLink', 'idElektrijade', 'ukupanBrojEkipa');
    }
	
	public function loadByDiscipline($idPodrucja, $idElektrijade) {
		try {
			$pov = $this->select()->where(array(
				"idPodrucja" => $idPodrucja,
				"idElektrijade" => $idElektrijade
			))->fetchAll();
			if (count($pov))
				$this->load ($pov[0]->getPrimaryKey());
			else
				$this->{$this->getPrimaryKeyColumn()} = null;
		} catch (app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function modifyRow($idElekPodrucje, $idPodrucja, $rezultatGrupni, $slikaLink, $idElektrijade, $ukupanBrojEkipa) {
		try {
			$this->load($idElekPodrucje);
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
			$e->errorInfo[2] = "Ne postoji traženi zapis!";
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function addRow($idPodrucja, $rezultatGrupni, $slikaLink, $idElektrijade, $ukupanBrojEkipa) {
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
	
	public function addImage($idElekPodrucje, $slikaLink) {
		try {
			$this->load($idElekPodrucje);
			$this->slikaLink = $slikaLink;
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