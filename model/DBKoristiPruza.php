<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBKoristiPruza extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'koristipruza';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idKoristiPruza';
    }
            
    public function getColumns(){
        return array ('idUsluge', 'idTvrtke', 'idElektrijade', 'iznosRacuna', 'valutaRacuna', 'nacinPlacanja', 'napomena');
    }
}


