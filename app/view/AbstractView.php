<?php

namespace app\view;

abstract class AbstractView implements View {
    
    /**
     * Polje oblika imeVarijableZaSet => vrijednost
     * 
     * @param array $array
     */
    public function __construct(array $array = array()) {
        foreach($array as $k => $v) {
            $metoda = 'set' . ucfirst($k);
            if (method_exists($this, $metoda)) {
                $this->$metoda($v);
            }
        }
    }
    /**
     * 
     * @return string
     */
    public function render() {
        ob_start();
        
        $this->outputHTML();
        
        return ob_get_clean();
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->render();
    }
    
    /**
     * @return void
     */
    protected abstract function outputHTML();
}