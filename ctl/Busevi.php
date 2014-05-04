<?php

namespace ctl;
use app\controller\Controller;

class Busevi implements Controller {

    private $grupa_html;
    private $bus_html;
    private $rjecnik_grupa;

    public function spremiRaspored() {
        $busevi = array();
        $busevi = post("busevi");
        //echo "TEST: " . count($test) . "<br>";
        echo "<pre>";
        print_r($busevi);
        echo "</pre>";

        //obrisi sve buseve iz baze
        $this->clearBuses();

        //echo "----------------------<br>";
        for ($i=0; $i < count($busevi); $i++) {
            $bus = $busevi[$i];
            //echo $bus["nazivBusa"] . "<br>";
            $idBusa = $this->addBus($bus["registracija"],
                                    $bus["brojMjesta"],
                                    $bus["brojBusa"],
                                    $bus["nazivBusa"]
                                    );
            echo $idBusa . "<br>";

            if(!array_key_exists("grupe", $bus)) {
                continue;
            }

            for ($j=0; $j < count($bus["grupe"]); $j++) {
                $grupa = $bus["grupe"][$j];
                //echo '&nbsp;&nbsp;' . $grupa["nazivGrupe"] . "<br>";

                $idGrupe = $this->addGroupToBus($idBusa,
                                                $grupa["nazivGrupe"]
                                                );
                echo "-- " . $idGrupe . "<br>";

                if(!array_key_exists("osobe", $grupa)) {
                    continue;
                }
                for ($k=0; $k < count($grupa["osobe"]); $k++) {
                    $osoba = $grupa["osobe"][$k];
                    //echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $osoba["idSudjelovanja"] . "<br>";
                    $this->addStudentToGroup($osoba["idSudjelovanja"],
                                            $idGrupe,
                                            $osoba["polazak"],
                                            $osoba["povratak"],
                                            $osoba["napomena"],
                                            $osoba["brojSjedala"]
                                            );
                }
            }
        }
    }

    private function getSudionici() {
        try {
            $elektrijada = new \model\DBElektrijada();
            $idElektrijade = $elektrijada->getCurrentElektrijadaId();

            if(is_null($idElektrijade))
                $idElektrijade = 0;

            $pdo = \app\db\DataBase::getInstance();
            $q = $pdo->prepare(
                    "SELECT idSudjelovanja as ID,
                            concat(ime, ' ', prezime) as ime_prezime,
                            (SELECT group_concat(concat(nazivPodrucja) separator ';')
                                FROM PodrucjeSudjelovanja as P NATURAL JOIN Podrucje
                                WHERE P.idSudjelovanja = ID) as podrucja,
                            (SELECT group_concat(concat(nazivPodrucja) separator ';')
                                FROM ImaAtribut as A NATURAL JOIN Podrucje
                                WHERE A.idSudjelovanja = ID) as atributi
                    FROM sudjelovanje AS S NATURAL JOIN osoba
                    WHERE idElektrijade = ? AND (SELECT COUNT(*) FROM PUTOVANJE AS PUT WHERE PUT.idSudjelovanja = S.idSudjelovanja) = 0"
                );
            $q->execute(array($idElektrijade));
            /*while($obj = $q->fetch(\PDO::FETCH_ASSOC)) {
                 echo ((string)$obj["ID"] . ": " . $obj["ime_prezime"] . " " . $obj["podrucja"] . $obj["atributi"] . "\r\n<br>");
            }*/
            return $q->fetchAll();
        } catch (\PDOException $e) {
            echo $e;
            //throw $e;
        }
    }

    private function getCode() {
        try {
            $bus_html = "";
            $grupa_html = "";
            $rjecnik_grupa = "";

            $pdo = \app\db\DataBase::getInstance();
            $q = $pdo->prepare(
                    "SELECT *, (SELECT COUNT(idSudjelovanja) FROM BUS
                                NATURAL JOIN BUSGRUPA
                                NATURAL JOIN PUTOVANJE
                                WHERE idBusa = VANJSKI.idBusa) as zauzeto
                    FROM BUS AS VANJSKI"
                );
            $q->execute();
            $busevi = $q->fetchAll();
            for ($i=0; $i < count($busevi); $i++) {

                $bus_inner_html = "";
                $bus = $busevi[$i];

                $q = $pdo->prepare(
                    "SELECT *, (SELECT COUNT(idSudjelovanja) FROM BUSGRUPA
                                    NATURAL JOIN PUTOVANJE
                                    WHERE idGrupe = VANJSKA.idGrupe) as velicina
                    FROM BUSGRUPA AS VANJSKA WHERE idBusa = ?"
                );
                $q->execute(array($bus->idBusa));
                $grupe = $q->fetchAll();

                for ($j=0; $j < count($grupe); $j++) {
                    $grupa = $grupe[$j];
                    $grupa_inner_html = "";

                    $q = $pdo->prepare(
                    "SELECT * FROM PUTOVANJE
                        JOIN SUDJELOVANJE ON SUDJELOVANJE.idSudjelovanja = PUTOVANJE.idSudjelovanja
                        JOIN OSOBA ON SUDJELOVANJE.idOsobe = OSOBA.idOsobe
                        WHERE PUTOVANJE.idGrupe = ?"
                    );
                    $q->execute(array($grupa->idGrupe));
                    $osobe = $q->fetchAll();

                    for ($k=0; $k < count($osobe); $k++) {
                        $osoba = $osobe[$k];

                        $grupa_inner_html .=
                            '<div class="student" id="' . $osoba->idSudjelovanja .'"><input type="checkbox" class="polazak" checked> <input type="checkbox" class="odlazak" checked> ' . $osoba->ime . ' ' . $osoba->prezime . '</div>';
                    }

                    $rjecnik_grupa .= "raspored.groupDictionary[\"" . $grupa->nazivGrupe . "\"] = " . $grupa->idGrupe . ";";

                    $grupa_html .=
                    '<div class="col-lg-4 col-md-6 col-xs-12 group hide-students disabled" data-status="disabled" id="' . $grupa->idGrupe . '">' .
                            '<div class="group-show-hide"><span class="glyphicon glyphicon-chevron-right"></span></div>' .
                            '<div class="group-name">' . $grupa->nazivGrupe . '</div>' .
                            '<div class="group-size">' . $grupa->velicina . '</div>' .
                            $grupa_inner_html .
                    '</div>';

                    $bus_inner_html .=
                    '<div class="bus-group" data-id="' . $grupa->idGrupe . '">' .
                            '<button type="button" class="btn btn-default btn-sm removeFromBus">' .
                                    '<span class="glyphicon glyphicon-arrow-left"></span>' .
                            '</button>' .
                            '<button type="button" class="btn btn-default btn-sm lockBusGroup">' .
                                    '<span class="glyphicon glyphicon-lock"></span>' .
                            '</button>' .
                            $grupa->nazivGrupe . ' - ' . $grupa->velicina .
                    '</div>';
                }

                $bus_html .=
                '<div class="col-xs-12 bus">' .
                    '<div class="bus-name">' . $bus->nazivBusa .'</div>' .
                    'Kapacitet:' .
                    '<span class="bus-used">' . $bus->zauzeto . '</span> / <span class="bus-capacity">' . $bus->brojMjesta .'</span>' .
                    '<div class="bus-percentage"><div class="bus-used-capacity"></div></div>' .
                    'Registracija:' .
                    '<span class="bus-plates">'. $bus->registracija .'</span>' .
                    $bus_inner_html .
                '</div>';
            }
            $this->grupa_html = $grupa_html;
            $this->bus_html = $bus_html;
            $this->rjecnik_grupa = $rjecnik_grupa;
        } catch (\PDOException $e) {
            echo $e;
            //throw $e;
        }
    }

    private function clearBuses() {
        try {
            $pdo = \app\db\DataBase::getInstance();
            $q = $pdo->prepare(
                    "DELETE FROM BUS"
                );
            $q->execute();
        } catch (\PDOException $e) {
            echo $e;
            //throw $e;
        }
    }

    private function addBus($registracija, $brojMjesta, $brojBusa, $nazivBusa) {
        try {
            $pdo = \app\db\DataBase::getInstance();
            $q = $pdo->prepare(
                    "INSERT INTO BUS(registracija, brojMjesta, brojBusa, nazivBusa) VALUES(?, ?, ?, ?)"
                );
            $q->execute(array($registracija, $brojMjesta, $brojBusa, $nazivBusa));
            return $pdo->lastInsertId("idBusa");
        } catch (\PDOException $e) {
            echo $e;
            //throw $e;
        }
    }

    private function addGroupToBus($idBusa, $nazivGrupe) {
        try {
            $pdo = \app\db\DataBase::getInstance();
            $q = $pdo->prepare(
                    "INSERT INTO BUSGRUPA(idBusa, nazivGrupe) VALUES(?, ?)"
                );
            $q->execute(array($idBusa, $nazivGrupe));
            return $pdo->lastInsertId("idGrupe");
        } catch (\PDOException $e) {
            echo $e;
            //throw $e;
        }
    }

    private function addStudentToGroup($idSudjelovanja, $idGrupe, $polazak, $povratak, $napomena, $brojSjedala) {
            try {
                $pdo = \app\db\DataBase::getInstance();
                $q = $pdo->prepare(
                        "INSERT INTO PUTOVANJE(idSudjelovanja, idGrupe, polazak, povratak, napomena, brojSjedala) VALUES(?, ?, ?, ?, ?, ?)"
                    );
                $q->execute(array($idSudjelovanja, $idGrupe, $polazak, $povratak, $napomena, $brojSjedala));
            } catch (\PDOException $e) {
                echo $e;
                //throw $e;
            }
    }

    private function checkForMessages() {
    }

    public function display() {
        //$this->addBuses("jedan;dva;tri", "50;50;50", "1;2;3");
        //$this->addToBus("11;12;13", "1;2;3", "1;1;0", "1;1;1", "mat;mat;mat");
        $this->checkForMessages();
        $this->getCode();
        echo new \view\busevi\BusGenerator(
            array(
            "sudionici" => $this->getSudionici(),
            "busevi" => $this->bus_html,
            "grupe" => $this->grupa_html,
            "rjecnikGrupa" => $this->rjecnik_grupa
            )
        );
    }
}