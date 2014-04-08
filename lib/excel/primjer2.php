<?php

/** ukljuci skriptu PHPExcel */
require('Classes/PHPExcel.php');


// kreiraj novi objekt
$objPHPExcel = new PHPExcel();


// dodaj podatke
echo " Dodaj podatke".'<br />'; 
 
$row=1;
 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$row, 'Ime')
			->setCellValue('B'.$row, 'Prezime')
			->setCellValue('C'.$row, 'Godine');
			
$row++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$row, 'Anastazijalijepa')
			->setCellValue('B'.$row, 'Ivic')
			->setCellValue('C'.$row, '22');
			
$row++;

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$row, 'Juraaaaaaaaaaaaaaaa')
			->setCellValue('B'.$row, 'Jurić')
			->setCellValue('C'.$row, '21');
			

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
