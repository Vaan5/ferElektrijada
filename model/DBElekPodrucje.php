<?php

        namespace model;
	use app\model\AbstractDBModel;
	
	class DBElekPodrucje extends AbstractDBModel {
	    
	    /**
	     *
	     * @var boolean 
	     */
	    private $isLoggedIn = false;
            
            public function getTable(){
                return 'elekpodrucje';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idElekPodrucje';
            }
            
            public function getColumns(){
                return array ('idPodrucja', 'rezultatGrupni', 'slikaLink', 'slikaBLOB', 'idElektrijade', 'idSponzora');
            }
			}
?>
