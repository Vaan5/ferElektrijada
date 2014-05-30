<?php

namespace model;
use app\model\AbstractDBModel;

class DBBusGrupa extends AbstractDBModel {

    public function getTable() {
        return 'busgrupa';
    }
    
    public function getPrimaryKeyColumn() {
        return ('idGrupe');
    }
    
    public function getColumns() {
        return array ('nazivGrupe', 'idBusa');
    }

    public function getAllGroups() {
        return $this->select()->fetchAll();
    }

    public function getGroups($idBusa) {
        try {
            $pdo = $this->getPdo();
            $q = $pdo->prepare(
                        "SELECT * FROM busgrupa WHERE idBusa = ?"
                    );
            $q->execute(array($idBusa));
            return $q->fetchAll();
        }
        catch (\PDOException $e) {
            throw $e;
        }
    }

    public function modifyRow($idGrupe, $nazivGrupe, $idBusa) {
        try {
            $this->load($idGrupe);
            $this->nazivGrupe = $nazivGrupe;
            $this->idBusa = $idBusa;
            $this->save();
        } catch (\app\model\NotFoundException $e) {     // whenever you use $this->load();
            $e = new \PDOException();
            $e->errorInfo[0] = '02000';
            $e->errorInfo[1] = 1604;
            $e->errorInfo[2] = "Zapis ne postoji!";
            throw $e;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function deleteRow($idGrupe) {
        try {
            $this->load($idGrupe);
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

    public function addRow($nazivGrupe, $idBusa) {
        try {
            $this->nazivGrupe = $nazivGrupe;
            $this->idBusa = $idBusa;
            $this->save();
            return $this->getPdo()->lastInsertId("idGrupe");
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}
