<?php

namespace app\db;

use \PDO;

class DataBase {
    
    /**
     *
     * @var \PDO 
     */
    private static $db;
    
    /**
     * 
     * @return \PDO
     */
    public static function getInstance() {
        if (null === self::$db) {
            try {
                self::$db = new PDO("mysql:dbname=ferElektrijada", "root", "", array(
                                                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                                             PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                                                             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                                                        ));
            } catch (PDOException $e) {
                echo "Greška kod spajanja: " . $e->getMessage();
                die();
            }
        }
        
        return self::$db;
    }
	
	private function __construct() {}
    
}