<?php

        namespace model;
	use app\model\AbstractDBModel;
	
	class DBImaSponzora extends AbstractDBModel {
	    
	    /**
	     *
	     * @var boolean 
	     */
	    private $isLoggedIn = false;
            
            public function getTable(){
                return 'imasponzora';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idImaSponzora';
            }
            
            public function getColumns(){
                return array('idSponzora','idKategorijeSponzora', 'idPromocije', 'idElektrijade', 'iznosDonacije', 'valutaDonacije', 'napomena');
            }
			}
?>

