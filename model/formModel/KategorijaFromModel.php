<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class KategorijaFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('tipKategorijeSponzora' => array('required', 'alnum'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'tipKategorijeSponzora':
                        return "Kategorija sponzora može sadržavati samo slova i znamenke!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
