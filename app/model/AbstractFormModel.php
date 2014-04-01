<?php

namespace app\model;

abstract class AbstractFormModel implements FormModel {
    
    protected $rulesArray;
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
            $pattern = '/^[a-zA-Z0-9_-]{3,16}$/u';
            return $this->test_pattern($pattern, $data);
    }
    
    protected function validateName($data) {
        if(isset($data) && $data !== '') {
            $pattern = '/^[A-ZČŠĐŽĆ][a-zčćžšđ]{2,}$/u';
            return $this->test_pattern($pattern, $data);
        }
        // if you didn't give me anything to check i'll just return true
        return true;
    }
    
    protected function validateMail($data) {
        if(isset($data) && $data !== '') {
            $pattern = '/^[A-Za-z0-9_.+-]+@(?:[A-Za-z0-9]+\.)+[A-Za-z]{2,3}$/';
            return $this->test_pattern($pattern, $data);
        }
        // if you didn't give me anything to check i'll just return true
        return true;
    }
    
    /**
     * checks if they are integers (> 10^6)
     * 
     * @param type $data
     * @return boolean
     */
    protected function validateNumbers($data) {
        if(isset($data) && $data !== '') {
            $pattern = '/^[0-9]{6,}$/';
            return $this->test_pattern($pattern, $data);
        }
        // if you didn't give me anything to check i'll just return true
        return true;
    }
    
    protected function validateJmbag($data) {
        if(isset($data) && $data !== '') {
            $pattern = '/^[0-9]{10}$/';
            return $this->test_pattern($pattern, $data);
        }
        // if you didn't give me anything to check i'll just return true
        return true;
    }
    
    protected function validateGender($data) {
        if(isset($data) && $data !== '') {
            $pattern = '/^[MŽ]{1}$/u';
            return $this->test_pattern($pattern, $data);
        }
        // if you didn't give me anything to check i'll just return true
        return true;
    }
    
    protected function validateDate($data) {
        if(isset($data) && $data !== '') {
            $pom = strtotime($data);
            return $pom == false ? false : true;
        }
        // if you didn't give me anything to check i'll just return true
        return true;
    }
    
    protected function validateOib($data) {
        if(isset($data) && $data !== '') {
            $pattern = '/^[0-9]{11}$/';
            return $this->test_pattern($pattern, $data);
        }
        // if you didn't give me anything to check i'll just return true
        return true;
    }

        /**
     * 
     * @param mixed $data
     * @return boolean
     */
    protected function validateRequired($data) {
        if ($data === false)
            return false;
        return true;
    }


    /**
     * @return array vracate polje oblika imePolja => array(nazivi pravila)
     * npr.: array('password' => array('password', 'username'))
     * tada se za clan testData['password'] pozivaju metode validatePassword i validateUsername
     * ako za neki clan nemate nikakvo pravilo onda ga oznacite sa 'safe'
     * npr. : array('email' => 'safe')
     * REQUIRED AKO POSTOJI MORA BITI PRVI U POLJU PRAVILA
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
            $prvi = true;
            foreach($metodeZaPozivanje as $metoda) {
                if($prvi) {
                    if ($metoda !== 'required' && $v === false)
                        break;          // ako nije neophodno polje i ako nije nista poslano ne testiraj ga
                }
                if($metoda === 'safe')
                    continue;
                $pov = call_user_func(array($this,'validate' . ucfirst($metoda)), $v);
                if($pov === false) return array($k => $metoda);
            }
            
        }
        
        return true;
    }
    
    public function getRules() {
        if ($this->rulesArray === null)
            return $this->rules();
        return $this->rulesArray;
    }
    
    public function setRules(array $r) {
        $this->rulesArray = $r;
    }
}
