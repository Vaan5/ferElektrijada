<?php

/** ukljuci skriptu PHPExcel */
require('Classes/PHPExcel.php');


// kreiraj novi objekt
$objPHPExcel = new PHPExcel();


// dodaj podatke 
echo " Dodaj podatke".'<br />'; 
 
$objPHPExcel->setActiveSheetIndex(0)
                          ->setCellValue('A1', 'Ime')
	          ->setCellValue('B1', 'Prezime')
	          ->setCellValue('C1', 'Godine')
	          ->setCellValue('A2', 'Ivo')
                          ->setCellValue('B2', 'Ivic')
                         ->setCellValue('C2', '22')
	         ->setCellValue('A3', 'Jura')
                         ->setCellValue('B3', 'Jurić')
	         ->setCellValue('C3', '21');

// preimenuj radni list
$objPHPExcel->getActiveSheet()->setTitle('Primjer');

//postavi aktivnim radnim listom prvi radni list
$objPHPExcel->setActiveSheetIndex(0);

// spremanje u .xlsx format
echo " Kreiraj .xlsx datoteku".'<br />';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
echo " Zapisano u  " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) .'<br />';


// spremanje u .xls format
echo " Kreiraj .xls datoteku".'<br />';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
echo " Zapisano u " , str_replace('.php', '.xls', pathinfo(__FILE__, PATHINFO_BASENAME)).'<br />';

// kraj
echo 'Datoteke se nalaze u ' , getcwd();
