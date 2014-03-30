<?php

        namespace model;
	use app\model\AbstractDBModel;
	
	class DBFunkcija extends AbstractDBModel {
	    
	    /**
	     *
	     * @var boolean 
	     */
	    private $isLoggedIn = false;
            
            public function getTable(){
                return 'funkcija';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idFunkcije';
            }
            
            public function getColumns(){
                return 'nazivFunkcije';
            }
			}
?>
