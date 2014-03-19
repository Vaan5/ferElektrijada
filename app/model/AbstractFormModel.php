<?php

namespace app\model;

abstract class AbstractFormModel implements FormModel {
    
    /**
     * @var array podaci za testiranje oblika nazivPolja => vrijednost
     * na primjer password => kaohaahd
     */
    protected $testData = array();
    
    /**
     * 
     * @param array $data podaci za testiranje oblika nazivPolja => vrijednost
     * na primjer password => kaohaahd
     */
    public function __construct(array $data) {
        $this->testData = $data;
    } 


    /**
     * Vrsi provjeru prema navedenom uzorku
     * vraca true ako podatak odgovara uzorku
     * inace false
     */
    protected function test_pattern($pattern, $data) {
            if (false === is_string($data)) {
                    return false;
            } else {
                    return preg_match($pattern, $data)?true:false;
            }
    }
    
    /**
     * Provjerava je li dobivena vrijednost ispravan password
     * Password ima min 6 max 18 znakova (slova i znamenke)
     * 
     * @param {mixed}						podatak koji je potrebno validirati
     * @return {boolean}					true ako je podatak zbilja ispravno napisan password
     */
    protected function validatePassword($data) {
            $pattern = '/^[a-zA-Z0-9]{3,18}$/';
            return $this->test_pattern($pattern, $data);
    }
    
    /**
     * Provjerava je li dobivena vrijednost ispravan username
     * Korisnicko ime ima min 3 max 16 znakova
     * 
     * @param {mixed}						podatak koji je potrebno validirati
     * @return {boolean}					true ako je podatak zbilja ispravno napisan username
     */
    protected function validateUsername($data) {
            $pattern = '/^[a-zA-Z0-9_-]{3,16}$/';
            return $this->test_pattern($pattern, $data);
    }
    
    /**
     * @return array vracate polje oblika imePolja => array(nazivi pravila)
     * npr.: array('password' => array('password', 'username'))
     * tada se za clan testData['password'] pozivaju metode validatePassword i validateUsername
     * ako za neki clan nemate nikakvo pravilo onda ga oznacite sa 'safe'
     * npr. : array('email' => 'safe')
     */
    protected abstract function rules();
    
    /**
     * 
     * @return boolean|array    ako nema sta za testirati vraca FALSE
     *                          ako je sve OK vraca TRUE
     *                          ako je negdje fail-alo vraca array(nazivPolja => nazivMetode)
     */
    public function validate() {
        if(count($this->testData) === 0) {
            return false;
        }
        
        $pravila = $this->rules();
        foreach($this->testData as $k => $v) {
            $metodeZaPozivanje = $pravila[$k];
            foreach($metodeZaPozivanje as $metoda) {
                if($metoda === 'safe')
                    continue;
                $pov = call_user_func(array($this,'validate' . ucfirst($metoda)), $v);
                if($pov === false) return array($k => $metoda);
            }
            
        }
        
        return true;
    }
}
