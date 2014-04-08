<?php

namespace view;
use app\view\AbstractView;

class ShowPdf extends AbstractView {

    private $pdf;
    
    protected function outputHTML() {
        $this->pdf->Output();
        die();
    }
    
    public function setPdf($pdf) {
        $this->pdf = $pdf;
        return $this;
    }

}