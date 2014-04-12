<?php

namespace view\ozsn;
use app\view\AbstractView;

class ContactSearch extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $kontakti;
    private $tvrtke;
    private $sponzori;
    private $mediji;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// BITNO !!!!!!!!!! ako je kontakti === null ispises samo obrazac, inace ispisi listu kontakata kao kod ContactList
	// DODAJ SETTERE
	// na kraju link za prikaz svih kontakata (displayContacts)
	// pazi kontakti moze biti prazno polje

    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
}