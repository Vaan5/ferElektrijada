<?php

namespace model;
use app\model\AbstractDBModel;

class DBSponElekPod extends AbstractDBModel {     
    
    // dodaj one prve tri metode
    
    public function getAll() {
	return $this->select()->fetchAll();
    }
    
    public function deleteAreaRow($idSponzora, $idElektrijade) {
	try {
            $pdo = $this->getPdo();
	    $q = $pdo->prepare("DELETE FROM sponelekpod WHERE idSponzora = :ids AND idElektrijade = :ide");
	    $q->bindValue(":ids", $idSponzora);
	    $q->bindValue(":ide", $idElektrijade);
	    $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}
