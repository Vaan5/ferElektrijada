<?php

namespace view\components;
use app\view\AbstractView;

class KontaktOsobeForm extends AbstractView {
    /**
     *
     * @var string url of the script to handle this form data
     */
    private $postAction;
    
    /**
     *
     * @var string submit button text
     */
    private $submitButtonText;
    

    
    protected function outputHTML() {
/**
 * Za mailove i brojeve mobitela napravi s js-om da se dinamicki mogu dodavati input type =text pri cemu im kao name staljvaj sljedece
 * mail1
 * mail2
 * mail3
 * ... (koliko ih ima)
 * te
 * mob1
 * mob2
 * mob3
 * itd koliko ih ima
 */
    }
    
    public function setPostAction($postAction) {
        $this->postAction = $postAction;
        return $this;
    }

    public function setSubmitButtonText($submitButtonText) {
        $this->submitButtonText = $submitButtonText;
        return $this;
    }
    
}
