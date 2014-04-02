<?php

namespace model;
use app\model\AbstractDBModel;

class DBTest extends AbstractDBModel {

    public function getTable(){
        return 'test';
    }

    public function getPrimaryKeyColumn(){
        return 'id';
    }

    public function getColumns(){
        return array('t1', 't2');
    }
    
    
    /**
     * Ante evo kako cemo rijesiti to sa greska i Exception-ima kojih nema u mysqlu
     * Znaci saljes signal
     * U TEXT_MESSAGE OBAVEZNO UTIPKAJ TEKST jer ce se on samo proslijediti na ispis (nemoj navodit ime tablica, ali mozes recimo ako je datumPocetka veci od datumKraja staviti i parametre u tekst)
     * SQL_STATE UVIJEK NEK JE 02000 (korisnicki exception)
     * error zasad je nebitan (budem josh vidio sta s tim)
     * da bi vidio ispis ukucaj u browser http://localhost/ferElektrijada/administrator/test
     */
    // skripta za tablicu
    
//    -- phpMyAdmin SQL Dump
//-- version 4.0.4
//-- http://www.phpmyadmin.net
//--
//-- Host: localhost
//-- Generation Time: Apr 02, 2014 at 04:56 PM
//-- Server version: 5.6.12-log
//-- PHP Version: 5.4.12
//
//SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
//SET time_zone = "+00:00";
//
//
///*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
///*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
///*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
///*!40101 SET NAMES utf8 */;
//
//--
//-- Database: `ferelektrijada`
//--
//
//-- --------------------------------------------------------
//
//--
//-- Table structure for table `test`
//--
//
//CREATE TABLE IF NOT EXISTS `test` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `t1` int(11) NOT NULL,
//  `t2` int(11) NOT NULL,
//  PRIMARY KEY (`id`),
//  UNIQUE KEY `t1` (`t1`)
//) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci AUTO_INCREMENT=1 ;
//
///*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
///*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
///*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

    
    // prvo skripta za proceduru
//    CREATE DEFINER=`root`@`localhost` PROCEDURE `t`(IN `a` INT)
//        NO SQL
//    SIGNAL SQLSTATE '02000'
//      SET MESSAGE_TEXT = 'Nesto bi', MYSQL_ERRNO = 1604
    
    // radi i sa triggerima koji se aktiviraju unutar procedure
//    CREATE TRIGGER `test` BEFORE INSERT ON `test`
// FOR EACH ROW SIGNAL SQLSTATE '02000'
//      SET MESSAGE_TEXT = 'Iz triggera koji je aktiviran u proceduri', MYSQL_ERRNO = 1604
    
//    CREATE DEFINER=`root`@`localhost` PROCEDURE `dodavanje`(IN `a` INT)
//    NO SQL
//INSERT INTO `ferelektrijada`.`test` (`id`, `t1`, `t2`) VALUES (NULL, '2', '2')
    public function metoda() {
        
        try{
         $pdo = $this->getPdo();
         $q = $pdo->prepare("CALL t(1)");
         //$q = $pdo->prepare("CALL dodavanje(1)");
         //$q = $pdo->prepare("CALL d()");
         $q->execute();
//         var_dump($q->fetchAll(\PDO::FETCH_CLASS, get_class($this)));
//          die();
        }catch (\PDOException $e) {
            var_dump('tu');
            var_dump($e->errorInfo); // tu ce biti josh i 02000 (indeks 0) i 1604 kao index 1
            var_dump($e->errorInfo[2]);  // <---- tu je sadrzaj od MESSAGE_TEXT
            var_dump($e);
            die();
            
    }
    }
}

