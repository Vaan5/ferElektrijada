<?php

namespace model;

class ExceptionHandlerModel implements \app\model\Model {
    /**
     *
     * @var \PDOException 
     */
    private $exception;
    
    /**
     *
     * @var string 
     */
    private $message;
    
    public function __construct(\PDOException $e, $message = null) {
        $this->exception = $e;
        $this->message = $message;
    }
    
    /**
     * Decyphers the PDOException object and generates an appropriate message
     * 
     * @return string
     */
    public function evaluate() {
        switch ($this->exception->errorInfo[0]) {
            case '02000':
                $this->message = $this->exception->errorInfo[2];
                break;
            case '23000':           // UNIQUE
                $string = $this->exception->errorInfo[2];
                $i1 = strpos($string, "'");
                $i2 = strpos($string, "'", $i1 + 1);
                $vrijednost = substr($string, $i1 + 1, $i2 - $i1 - 1);
                $vrijednost = __($vrijednost);
		if (strpos($vrijednost, "-") !== false) {
		    $this->message = "Zapis s takvim podacima već postoji!";
		} else {
		    $this->message = "Zapis s vrijednošću {$vrijednost} već postoji!";
		}
                break;
            default :
                if (!(isset($this->message) && $this->message !== null))
                    $this->message = "Dogodila se pogreška prilikom rada s bazom podataka! Pokušajte ponovno!";
        }
        return $this->message;        
    }
    
    public function __toString() {
        return $this->evaluate();
    }
}
