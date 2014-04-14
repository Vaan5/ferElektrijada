<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class ContactSearchFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('search' => array('alnum'),
				    'idSponzora' => array('numbers'),
				    'idTvrtke' => array('numbers'),
				    'idMedija' => array('numbers'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'search':
                        return "Pojam pretrage smije sadržavati samo slova i znamenke!";
		    case 'idSponzora':
		    case 'idTvrtke':
		    case 'idMedija':
			return "Neispravan identifikator!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
