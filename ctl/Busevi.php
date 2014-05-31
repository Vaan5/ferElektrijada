<?php

namespace ctl;
use app\controller\Controller;

class Busevi implements Controller {

    private $errorMessage;
    //private $resultMessage;

    private function checkRole() {
        // you must be logged in, and an Ozsn member with or without leadership
        $o = new \model\DBOsoba();
        if (!((\model\DBOsoba::isLoggedIn() && (\model\DBOsoba::getUserRole() === 'O' ||
            \model\DBOsoba::getUserRole() === 'OV') && $o->isActiveOzsn(session("auth"))) || (\model\DBOsoba::isLoggedIn() && \model\DBOsoba::getUserRole() === 'A' ))) {
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
        }
    }


    private function validateInput($busevi) {
        $naziviBuseva = array();
        $registracije = array();
        $naziviGrupa = array();
        for ($i=0; $i < count($busevi); $i++) {
            $bus = $busevi[$i];


            if(
                empty($bus["registracija"]) ||
                !is_numeric($bus["brojMjesta"]) ||
                !is_numeric($bus["brojBusa"]) ||
                empty($bus["nazivBusa"])
                )
                return false;

            array_push($registracije, $bus["registracija"]);
            array_push($naziviBuseva, $bus["nazivBusa"]);

            if(!array_key_exists("grupe", $bus)) {
                continue;
            }
            for ($j=0; $j < count($bus["grupe"]); $j++) {
                $grupa = $bus["grupe"][$j];

                if( empty($grupa["nazivGrupe"]) )
                    return false;

                array_push($naziviGrupa, $grupa["nazivGrupe"]);

                if(!array_key_exists("osobe", $grupa)) {
                    continue;
                }
                for ($k=0; $k < count($grupa["osobe"]); $k++) {
                    $osoba = $grupa["osobe"][$k];

                    if(
                        !is_numeric($osoba["idSudjelovanja"]) ||
                        !is_numeric($osoba["brojSjedala"]) ||
                        !is_numeric($osoba["polazak"]) ||
                        !is_numeric($osoba["povratak"])
                        )
                        return false;
                }
            }
        }
        return (
                    count($registracije) == count(array_unique($registracije)) &&
                    count($naziviBuseva) == count(array_unique($naziviBuseva)) &&
                    count($naziviGrupa) == count(array_unique($naziviGrupa))
                );
    }

    public function spremiRaspored() {
        try {
            $this->checkRole();
            $busevi = array();
            $busevi = post("busevi");
            //echo "<pre>";
            //var_dump($busevi);
            //echo "</pre>";

            if($this->validateInput($busevi)) {

                $busModelClear = new \model\DBBus();
                $busModelClear->clearBuses();

                for ($i=0; $i < count($busevi); $i++) {
                    $bus = $busevi[$i];

                    $busModel = new \model\DBBus();
                    $idBusa = $busModel->addRow(
                                                $bus["registracija"],
                                                intval($bus["brojMjesta"]),
                                                intval($bus["brojBusa"]),
                                                $bus["nazivBusa"]
                                            );

                    if(!array_key_exists("grupe", $bus)) {
                        continue;
                    }
                    for ($j=0; $j < count($bus["grupe"]); $j++) {
                        $grupa = $bus["grupe"][$j];
                        $grupaModel = new \model\DBBusGrupa();

                        $idGrupe = $grupaModel->addRow(
                                                        $grupa["nazivGrupe"],
                                                        $idBusa
                                                    );

                        if(!array_key_exists("osobe", $grupa)) {
                            continue;
                        }
                        for ($k=0; $k < count($grupa["osobe"]); $k++) {
                            $osoba = $grupa["osobe"][$k];

                            $putovanjeModel = new \model\DBPutovanje();
                            $putovanjeModel->addRow(
                                                    intval($osoba["idSudjelovanja"]),
                                                    $idGrupe,
                                                    intval($osoba["polazak"]),
                                                    intval($osoba["povratak"]),
                                                    $osoba["napomena"],
                                                    intval($osoba["brojSjedala"])
                                                );
                        }
                    }
                }
                echo "Uspješno spremljeno!";
            } else {
                echo "Ulazni podatci nisu ispravni! Izmjene nisu spremljene!";
            }
    } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->createMessage($handler, "d2", "busevi", "");
    }
    }

    private function getBusevi() {
        try {
            $this->checkRole();
            $busModel = new \model\DBBus();
            $busevi = $busModel->getAllBusesAsArray();
            for ($i=0; $i < count($busevi); $i++) {
                $bus = $busevi[$i];

                $grupaModel = new \model\DBBusGrupa();
                $grupe = $grupaModel->getGroups($bus->idBusa);

                for ($j=0; $j < count($grupe); $j++) {
                    $grupa = $grupe[$j];

                    $putovanjeModel = new \model\DBPutovanje();
                    $osobe = $putovanjeModel->getPutovanja($grupa->idGrupe);

                    $grupe[$j]->osobe = $osobe;
                }

                $busevi[$i]->grupe = $grupe;
            }
            return $busevi;
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->createMessage($handler, "d2", "busevi", "");
        }
    }

    private function checkForMessages() {
        switch(get("msg")) {
            case 'excep':
                if(isset($_SESSION['exception'])) {
                    $e = unserialize($_SESSION['exception']);
                    unset($_SESSION['exception']);
                    $this->errorMessage = $e;
                }
            default:
                break;
        }
    }

    private function createMessage($message, $type = 'd1', $controller = null, $action = null) {
        $handler = new \model\ExceptionHandlerModel(new \PDOException(), (string)$message);
        $_SESSION["exception"] = serialize($handler);
        if ($type === 'd2') {
            preusmjeri(\route\Route::get('d2')->generate(array(
                "controller" => $controller
                )) . "?msg=excep");
        } else {
             preusmjeri(\route\Route::get('d1')->generate() . "?msg=excep");
        }
    }

    public function display() {
        $busevi = array();
        $sudionici = array();
        try {
            //$this->checkRole();
            $this->checkForMessages();

            if(!isset($this->errorMessage)) {

                $busevi = $this->getBusevi();
                $putovanjeModel = new \model\DBPutovanje();
                $sudionici = $putovanjeModel->getSudioniciBezPutovanja();
            }
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->createMessage($handler, "d2", "busevi", "");
        }

        echo new \view\Main(array(
                "body" => new \view\busevi\BusGenerator(
                            array(
                            "sudionici" => $sudionici,
                            "busevi" => $busevi,
                            "errorMessage" => $this->errorMessage
                            )),
                "title" => "Raspored po Busevima",
        ));
    }
}