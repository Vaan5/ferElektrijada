<?php

        namespace model;
	use app\model\AbstractDBModel;
	
	class DBSudjelovanje extends AbstractDBModel {
	    
	    /**
	     *
	     * @var boolean 
	     */
	    private $isLoggedIn = false;
            
            public function getTable(){
                return 'zavod';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idZavoda';
            }
            
            public function getColumns(){
                return array('nazivZavoda','skraceniNaziv');
            }
			}
?>
