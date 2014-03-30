<?php

        namespace model;
	use app\model\AbstractDBModel;
	
	class DBVelMajice extends AbstractDBModel {
	    
	    /**
	     *
	     * @var boolean 
	     */
	    private $isLoggedIn = false;
            
            public function getTable(){
                return 'velmajice';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idVelicine';
            }
            
            public function getColumns(){
                return 'velicina';
            }
			}
?>
