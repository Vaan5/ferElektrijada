<?php

namespace view\components;
use app\view\AbstractView;

class TvrtkaAssignForm extends AbstractView {
    private $route;
    private $submitButtonText;

    protected function outputHTML() {

    }
    
    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }

    public function setSubmitButtonText($submitButtonText) {
        $this->submitButtonText = $submitButtonText;
        return $this;
    } 

}