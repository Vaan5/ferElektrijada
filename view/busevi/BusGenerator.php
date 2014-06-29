<?php

namespace view\busevi;
use app\view\AbstractView;

class BusGenerator extends AbstractView {

    private $errorMessage;
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    private $sudionici;
    /**
     *
     * @param array $sudionici
     */
    public function setSudionici($sudionici) {
        $this->sudionici = $sudionici;
    }

    private $baseUrl;
    /**
     *
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    private $busevi;
    private $buseviHtml;
    private $grupeHtml;
    private $rjecnikGrupa;
    /**
     *
     * @param array $busevi
     */
    public function setBusevi($busevi) {
        $this->busevi = $busevi;
        try {
            $bus_html = "";
            $grupa_html = "";
            $rjecnik_grupa = "";

            for ($i=0; $i < count($busevi); $i++) {

                $bus_inner_html = "";
                $bus = $busevi[$i];
                $grupe = $bus->grupe;

                $zauzeto = 0;

                for ($j=0; $j < count($grupe); $j++) {
                    $grupa = $grupe[$j];
                    $grupa_inner_html = "";

                    $osobe = $grupa->osobe;

                    $polazakCount = 0;
                    $povratakCount = 0;
                    for ($k=0; $k < count($osobe); $k++) {
                        $osoba = $osobe[$k];

                        $polazak = '';
                        if(strcmp($osoba->polazak, "1") == 0)
                        {
                            $polazak = 'checked';
                            $polazakCount++;
                        }
                        $povratak = '';
                        if(strcmp($osoba->povratak, "1") == 0)
                        {
                            $povratak = 'checked';
                            $povratakCount++;
                        }

                        $grupa_inner_html .=
                            '<div class="student" data-id="'
                            . $osoba->idSudjelovanja
                            .'"><input type="checkbox" class="polazak" ' . $polazak . '>
                            <input type="checkbox" class="odlazak" ' . $povratak . '> '
                            . $osoba->ime . ' '
                            . $osoba->prezime .
                            ' <input type="text" value="' .
                                    $grupa->nazivGrupe .
                            '"></div>';
                    }
                    $velicina = $povratakCount;
                    if($polazakCount > $velicina)
                        $velicina = $polazakCount;

                    $zauzeto += $velicina;

                    $rjecnik_grupa .= "raspored.groupDictionary[\"" . $grupa->nazivGrupe . "\"] = " . $grupa->idGrupe . ";";

                    $grupa_html .=
                    '<div class="col-lg-4 col-md-6 col-xs-12 group hide-students disabled" data-status="disabled" id="' . $grupa->idGrupe . '">' .
                            '<div class="group-show-hide"><span class="glyphicon glyphicon-chevron-right"></span></div>' .
                            '<div class="group-name">' . $grupa->nazivGrupe . '</div>' .
                            '<div class="group-size">' . $velicina . '</div>' .
                            $grupa_inner_html .
                    '</div>';

                    $bus_inner_html .=
                    '<div class="bus-group" data-id="' . $grupa->idGrupe . '">' .
                            '<input type="text" class="busGroupOrder" value="N" size="2" maxlength="2">' .
                            '<button type="button" class="btn btn-default btn-sm removeFromBus">' .
                                    '<span class="glyphicon glyphicon-arrow-left"></span>' .
                            '</button>' .
                            '<button type="button" class="btn btn-default btn-sm lockBusGroup">' .
                                    '<span class="glyphicon glyphicon-lock"></span>' .
                            '</button>' .
                            $grupa->nazivGrupe . ' - ' . $velicina .
                    '</div>';
                }

                $bus_html .=
                '<div class="col-xs-12 bus">' .
                    '<div class="bus-name">' . $bus->nazivBusa .'</div>' .
                    'Kapacitet:' .
                    '<span class="bus-used">' . $zauzeto . '</span> / <span class="bus-capacity">' . $bus->brojMjesta .'</span>' .
                    '<div class="bus-percentage"><div class="bus-used-capacity"></div></div>' .
                    'Registracija:' .
                    '<span class="bus-plates">'. $bus->registracija .'</span>' .
                    $bus_inner_html .
                '</div>';
            }
            $this->grupeHtml = $grupa_html;
            $this->buseviHtml = $bus_html;
            $this->rjecnikGrupa = $rjecnik_grupa;
        } catch (\PDOException $e) {
            //echo $e;
            throw $e;
        }
    }

    /**
     * @return string html sadrzaj
     */
    protected function outputHTML() {
        ?>

        <?php
                            echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
                            ));

            if(strlen($this->errorMessage) == 0) {
        ?>

        <style type="text/css">
          @import url("<?php echo $this->baseUrl; ?>assets/busevi/css/style.css");
        </style>
        <div id="busevi-container">
            <div id="loader"><div id="loader_img"></div></div>
            <div class="container button-navbar">
                <div class="row button-menu">
                    <div class="col-xs-12 button-group fullWidth">
                        <div class="name">OPERACIJE</div>
                        <div class="button-row">
                            <button type="button" class="btn btn-default btn-md" data-toggle="tooltip" data-placement="right" title="Pokreni automatsku raspodjelu" id="fillBuses">
                                <span class="glyphicon glyphicon-stats orange"></span> Pokreni
                            </button>
                            <button type="button" class="btn btn-default btn-md" data-toggle="tooltip" data-placement="right" title="Spremi izmjene u bazu podataka" id="saveSchedule">
                                <span class="glyphicon glyphicon-save green"></span> Spremi
                            </button>
                        </div>
                    </div>
                    <div class="col-xs-4 button-group offset-top">
                        <div class="name">SUDIONICI</div>
                        <div class="button-row">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Prebaci u aktivnu podgrupu" id="addToGroup">
                                <span class="glyphicon glyphicon-download green"></span> Prebaci u 
                                <span id="activeGroupName"></span>
                            </button>

                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Ukloni selekciju iz aktivne podgrupe" id="removeFromGroup">
                                <span class="glyphicon glyphicon-upload red"></span> Ukloni
                            </button>
                        </div>
                    </div>
                    <div class="col-xs-4 button-group">
                        <div class="name">PODGRUPE</div>
                        <div class="button-row">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Dodaj novu podgrupu" id="addGroup">
                                <span class="glyphicon glyphicon-th-list green"></span> Dodaj
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Promijeni ime podgrupe" id="setGroupName">
                                <span class="glyphicon glyphicon-pencil blue"></span> Ime
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Ukloni podgrupu" id="removeGroup">
                                <span class="glyphicon glyphicon-eject red"></span> Ukloni
                            </button>
                            <button type="button" class="btn btn-success btn-sm" id="addToBus" data-placement="right" title="Prebaci podgrupu u aktivni bus" id="removeGroup">
                                <span class="glyphicon glyphicon-transfer"></span> Prebaci
                            </button>
                        </div>
                    </div>
                    <div class="col-xs-4 button-group">
                        <div class="name">BUSEVI</div>
                        <div class="button-row">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Dodaj novi bus" id="addBus">
                                <span class="glyphicon glyphicon-hdd green"></span> Dodaj
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Očisti bus (zaključane grupe ostaju u busu)" id="clearBus">
                                <span class="glyphicon glyphicon-step-backward blue"></span> Očisti
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Očisti sve buseve (zaključane grupe ostaju u busevima)" id="clearAllBuses">
                                <span class="glyphicon glyphicon-fast-backward orange"></span> Očisti sve
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Postavi naziv busa" id="setBusName">
                                <span class="glyphicon glyphicon-pencil blue"></span> Naziv
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Postavi kapacitet busa" id="setBusCapacity">
                                <span class="glyphicon glyphicon-pencil blue"></span> Kapacitet
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Postavi oznaku busa" id="setBusPlates">
                                <span class="glyphicon glyphicon-pencil blue"></span> Oznaka
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right" title="Ukloni bus" id="removeBus">
                                <span class="glyphicon glyphicon-trash red"></span> Ukloni
                            </button>
                        </div>
                    </div>
                    <!--
                    <div class="col-xs-12 button-group fullWidth">
                        <div class="name">ZATVORI</div>
                        <div class="button-row">
                            <a href="/ferElektrijada" class="btn btn-default btn-md" data-toggle="tooltip" data-placement="right" title="Povratak na početnu stranicu">
                                <span class="glyphicon glyphicon-arrow-left red"></span> Izlaz
                            </a>
                        </div>
                    </div>
                -->
                </div>
            </div>
            <div class="offset-top right-content">
                <div class="row">
                    <div class="col-xs-8 sudionici">
                        <div class="row-fluid">

                            <div class="col-xs-12 top">SUDIONICI</div>

                            <div class="col-xs-12 group-ignore" id="unassigned">
                                <div class="group-name">Neraspoređeni sudionici
                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="right"
                                            title="Pokreni automatsku raspodjelu u podgrupe. Bit će razvrstani svi koji u nazivu
                                             podgrupe nemaju točka-zarez (;)" id="generateGroups">
                                        <span class="glyphicon glyphicon-th-large"></span> Automatski rasporedi u podgrupe
                                    </button>
                                </div>
                                <?php
                                    for ($i=0; $i < count($this->sudionici); $i++) {
                                            $obj = $this->sudionici[$i];
                                            //echo ((string)$obj->ID . ": " . $obj->ime_prezime . " " . $obj->podrucja . $obj->atributi . "\r\n<br>");
                                            $array1 = explode(";", $obj->podrucja);
                                            $array2 = explode(";", $obj->atributi);
                                            $p = array_unique(array_merge($array1, $array2));
                                            echo '<div class="student" data-id="' . $obj->ID . '">' .
                                                 '<input type="checkbox" class="polazak" checked>' .
                                                 '<input type="checkbox" class="odlazak" checked> ' .
                                                 $obj->ime_prezime .
                                                 ' <input type="text" value="' .
                                                    join(";", array_filter($p)) .
                                                 '"></div>';
                                    }
                                ?>

                                <!-- <div class="student" data-id="8"><input type="checkbox" class="polazak" checked> <input type="checkbox" class="odlazak" checked> Francuski Ključ <input type="text" value="test;test2"></div> -->
                            </div>

                            <div id="group-container">
                                <?php echo $this->grupeHtml; ?>
                                <!--
                                <div class="col-lg-4 col-md-6 col-xs-12 group hide-students" id="group1" data-status="enabled">
                                    <div class="group-show-hide"><span class="glyphicon glyphicon-chevron-right"></span></div>
                                    <div class="group-name">Analiza elektroenergetskih sustava</div>
                                    <div class="group-size">4</div>
                                    <div class="student" data-id="9"><input type="checkbox" class="polazak" checked> <input type="checkbox" class="odlazak" checked> Student Studentić</div>
                                </div>
                                -->
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-4 busevi">
                        <div class="row-fluid">
                            <div class="col-xs-12 top">BUSEVI
                                <button type="button" class="btn btn-default btn-sm"  data-toggle="tooltip" data-placement="left" title="Sortiraj grupe u svim autobusima" id="sortBusGroups">
                                    <span class="glyphicon glyphicon-sort-by-order"></span> Sort
                                </button>
                            </div>
                            <div id="bus-container">
                                <?php
                                    echo $this->buseviHtml;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <script type="text/javascript">
                var baseUrl = "/";
                $(document).ready( function() {
                    <?php echo $this->rjecnikGrupa; ?>
                    baseUrl = "<?php echo $this->baseUrl; ?>";
                });
            </script>
            <script src="<?php echo $this->baseUrl; ?>assets/busevi/js/jquery.tinysort.js" charset="UTF-8"></script>
            <script src="<?php echo $this->baseUrl; ?>assets/busevi/js/raspored.lib.js" charset="UTF-8"></script>
            <script src="<?php echo $this->baseUrl; ?>assets/busevi/js/raspored.algorithm.js" charset="UTF-8"></script>
            <script src="<?php echo $this->baseUrl; ?>assets/busevi/js/raspored.save.js" charset="UTF-8"></script>
            <script src="<?php echo $this->baseUrl; ?>assets/busevi/js/raspored.init.js" charset="UTF-8"></script>


<?php
        }
    }
}
