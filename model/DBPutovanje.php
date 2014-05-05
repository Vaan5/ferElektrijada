<?php

namespace model;
use app\model\AbstractDBModel;

class DBPutovanje extends AbstractDBModel {

    public function getTable() {
        return 'putovanje';
    }

    public function getPrimaryKeyColumn() {
        return ('idPutovanja');
    }

    public function getColumns() {
        return array ('idSudjelovanja','idGrupe','polazak', 'povratak', 'napomena','brojSjedala');
    }

    public function loadIfExists($primaryKey) {
        try {
            $this->load($primaryKey);
        } catch (\app\model\NotFoundException $e) {
            return;
        } catch (\PDOException $e) {
            return;
        }
    }

    public function getAllPutovanja() {
        return $this->select()->fetchAll();
    }

    public function getPutovanja($idGrupe) {
        try
        {
            $pdo = $this->getPdo();
            $q = $pdo->prepare(
                        "SELECT SUDJELOVANJE.idSudjelovanja,
                                ime,
                                prezime,
                                povratak,
                                polazak
                                FROM PUTOVANJE
                            JOIN SUDJELOVANJE
                                ON SUDJELOVANJE.idSudjelovanja = PUTOVANJE.idSudjelovanja
                            JOIN OSOBA
                                ON SUDJELOVANJE.idOsobe = OSOBA.idOsobe
                            WHERE PUTOVANJE.idGrupe = ?"
                        );
            $q->execute(array($idGrupe));
            return $q->fetchAll();
        }
        catch (\PDOException $e) {
            throw $e;
        }
    }

    public function modifyRow($idPutovanja, $idSudjelovanja, $idGrupe, $polazak, $povratak, $napomena, $brojSjedala) {
        try {
            $this->load($idPutovanja);
            $this->idSudjelovanja = $idSudjelovanja;
            $this->idGrupe = $idGrupe;
            $this->polazak = $polazak;
            $this->povratak = $povratak;
            $this->napomena = $napomena;
            $this->brojSjedala = $brojSjedala;
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

    public function deleteRow($idPutovanja) {
        try {
            $this->load($idPutovanja);
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

    public function addRow($idSudjelovanja, $idGrupe, $polazak, $povratak, $napomena, $brojSjedala) {
        try {
            $this->idSudjelovanja = $idSudjelovanja;
            $this->idGrupe = $idGrupe;
            $this->polazak = $polazak;
            $this->povratak = $povratak;
            $this->napomena = $napomena;
            $this->brojSjedala = $brojSjedala;
            $this->save();
            return $this->getPdo()->lastInsertId("idBusa");
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function getSudioniciBezPutovanja() {
        try {
            $elektrijada = new \model\DBElektrijada();
            $idElektrijade = $elektrijada->getCurrentElektrijadaId();

            if(is_null($idElektrijade))
                $idElektrijade = 0;

            $pdo = $this->getPdo();
            $q = $pdo->prepare(
                    "SELECT idSudjelovanja as ID,
                            concat(ime, ' ', prezime) as ime_prezime,
                            (SELECT group_concat(concat(nazivPodrucja) separator ';')
                                FROM PodrucjeSudjelovanja as P NATURAL JOIN Podrucje
                                WHERE P.idSudjelovanja = ID) as podrucja,
                            (SELECT group_concat(concat(nazivPodrucja) separator ';')
                                FROM ImaAtribut as A NATURAL JOIN Podrucje
                                WHERE A.idSudjelovanja = ID) as atributi
                    FROM sudjelovanje AS S
                    NATURAL JOIN osoba
                    WHERE idElektrijade = ?
                        AND (SELECT COUNT(*)
                                FROM PUTOVANJE AS PUT
                                WHERE PUT.idSudjelovanja = S.idSudjelovanja) = 0"
                );
            $q->execute(array($idElektrijade));
            return $q->fetchAll();
        } catch (\PDOException $e) {
            //echo $e;
            throw $e;
        }
    }
}
