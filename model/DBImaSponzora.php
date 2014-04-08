<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBImaSponzora extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'imasponzora';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idImaSponzora';
    }
            
    public function getColumns(){
        return array('idSponzora','idKategorijeSponzora', 'idPromocije', 'idElektrijade', 'iznosDonacije', 'valutaDonacije', 'napomena');
    }
    
    public function addRow($idSponzora, $idKategorijeSponzora, $idPromocije, $idElektrijade, $iznosDonacije,
	    $valutaDonacije, $napomena) {
	try {
	    $atributi = $this->getColumns();
	    foreach($atributi as $a) {
		$this->{$a} = ${$a};
	    }
	    if ($this->napomena === '' || $this->napomena === ' ')
		$this->napomena = NULL;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}


