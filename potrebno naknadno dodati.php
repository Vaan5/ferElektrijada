<?php

// u OBAVLJAFUNKCIJU
public function addNewRow($idOsobe, $idFunkcije, $idElektrijade) {
    $this->{$this->getPrimaryKeyColumn()} = null;
    $atributi = $this->getColumns();
    foreach($atributi as $a) {
        $this->{$a} = ${$a};
    }
    $this->save();
};