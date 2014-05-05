<?php

namespace ctl;
use app\controller\Controller;

class Busevi implements Controller {

    public function spremiRaspored() {
        try {
            $busevi = array();
            $busevi = post("busevi");
            echo "<pre>";
            print_r($busevi);
            echo "</pre>";

            $busModelClear = new \model\DBBus();
            $busModelClear->clearBuses();

            for ($i=0; $i < count($busevi); $i++) {
                $bus = $busevi[$i];

                $busModel = new \model\DBBus();
                $idBusa = $busModel->addRow(
                                            $bus["registracija"],
                                            $bus["brojMjesta"],
                                            $bus["brojBusa"],
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
                                                $osoba["idSudjelovanja"],
                                                $idGrupe,
                                                $osoba["polazak"],
                                                $osoba["povratak"],
                                                $osoba["napomena"],
                                                $osoba["brojSjedala"]
                                            );
                    }
                }
            }
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    private function getBusevi() {
        try {
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
            throw $e;
        }
    }

    private function checkForMessages() {
    }

    public function display() {
        $this->checkForMessages();

        $busevi = $this->getBusevi();
        $putovanjeModel = new \model\DBPutovanje();
        $sudionici = $putovanjeModel->getSudioniciBezPutovanja();
        echo new \view\busevi\BusGenerator(
            array(
            "sudionici" => $sudionici,
            "busevi" => $busevi
            )
        );
    }
}