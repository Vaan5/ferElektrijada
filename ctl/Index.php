<?php

namespace ctl;
use app\controller\Controller;

class Index implements Controller {
    
    private $resultMessage;
    
    private $errorMessage;
    
    private function checkForMessages() {
        switch (get('msg')) {
            case 'logsuccess':
                $this->resultMessage = 'Uspješno ste se prijavili!';
                break;
            case 'accessDenied':
                $this->errorMessage = 'Nemate odgovarajuće ovlasti!';
                break;
            case 'ozsnAddedSucc':
                $this->resultMessage = 'Uspješno dodan član odbora!';
                break;
            case 'elekAddSucc':
                $this->resultMessage = "Uspješno dodana nova Elektrijada!";
                break;
			case 'succel':
                $this->resultMessage = "Uspješno izmijenjeni podaci o Elektrijadi!";
                break;
            case 'dunno':
                $this->errorMessage = "Tražena osoba ne postoji!";
                break;
	    case 'typeconf':
                $this->errorMessage = "Nepoznati tip datoteke!";
                break;
            case 'notOzsn':
                $this->errorMessage = "Osoba nije član OZSN-a";
                break;
            case 'err':
                $this->errorMessage = "Zapis nije moguće dodati!";
                break;
            case 'e':
                $this->errorMessage = "Ne postoji zapis s predanim identifikatorom!";
                break;
            case 'profSucc':
                $this->resultMessage = "Uspješno ažurirani vlastiti podaci!";
                break;
            case 'ozsnl':
                $this->resultMessage = "Uspješno obnovljene ovlasti svim prošlogodišnjim članovima odbora!";
                break;
            case 'succContact':
                $this->resultMessage = "Uspješno dodan novi kontakt!";
                break;
	    case 'assignS':
		$this->resultMessage = "Uspješno zabilježeno korištenje usluga tvrtke!";
		break;
            case 'excep':
                if(isset($_SESSION['exception'])) {
                    $e = unserialize($_SESSION['exception']);   // don't forget 'use \PDOException;'
                    unset($_SESSION['exception']);
                    $this->errorMessage = $e;
                }
            default:
                $this->resultMessage = null;
                break;
        }
    }
    
    public function display() {
        $this->checkForMessages();
        
        echo new \view\Main(array(
            "body" => new \view\Index(array(
                "varijablaPrenesenaIzControllera" => "Varijabla koju sam mogao staviti da sam htio! => izmijeniti ctl\Index!",
                "resultMessage" => $this->resultMessage,
                "errorMessage" => $this->errorMessage
            )),
            "title" => "FER"
        ));
    }
}