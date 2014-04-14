<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class TvrtkaFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('imeTvrtke' => array('required', 'words'),
					'adresaTvrtke' => array('required', 'words'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'imeTvrtke':
                        return "Naziv tvrtke može sadržavati samo znamenke i slova!";
		    case 'adresaTvrtke':
                        return "Adresa tvrtke može sadržavati samo znamenke i slova!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
