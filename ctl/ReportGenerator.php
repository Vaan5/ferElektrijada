<?php

namespace ctl;
use app\controller\Controller;
use \tFPDF;

class ReportGenerator implements Controller {
    
    private $errorMessage;
    private $resultMessage;
    
    private function checkRole() {
        // you must be logged in, and an Ozsn member with or without leadership
        if (!(\model\DBOsoba::isLoggedIn() && (\model\DBOsoba::getUserRole() === 'O' || \model\DBOsoba::getUserRole() === 'OV'))) {
            preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
        }
    }
    
    /**
     * function to check if get("id") is a number
     */
    private function idCheck($akcija) {
	$validator = new \model\formModel\IdValidationModel(array("id" => get("id")));
	$pov = $validator->validate();
	if ($pov !== true) {
	    $message = $validator->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => $akcija
            )) . "?msg=excep");
	}
    }
    
    private function checkMessages() {
        switch(get("msg")) {
            case 'excep':
                if(isset($_SESSION['exception'])) {
                    $e = unserialize($_SESSION['exception']);
                    unset($_SESSION['exception']);
                    $this->errorMessage = $e;
                }
            default:
                break;
        }
    }
    
    private function generatePdf(array $polje) {
	require_once "lib/tfpdf/tfpdf.php";
	
	$pdf = new tFPDF('P','mm',array(200,300));

	//poseban font koji prikazuje utf-8 znakove
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->SetFont('DejaVu','',10);

	$size=count($polje); //broj redaka
	$size1=count($polje[0]); //broj stupaca

	//********************************************************************************
	// eksperiment sa šririnama - izdvojiti max šrinu podatka u stupcu 

	//širine podataka u ćeliji u milimetrima - GetStringWidth
	for($i=0;$i < $size1; $i++){
		for($j=0;$j < $size; $j++){
			$var=$pdf->GetStringWidth($polje[$j][$i]);
			$w[]=$var;  //u w pohranjene širine ćelija - po stupcima

		}

	}
	//$pdf->Ln();
	//$pdf->Write(10,$w[8]);

	//podijeli na manja polja - svako polje će imati broj elemenata jednak broju redaka
	$rows = array_chunk($w,$size);
	//print_r($rows);

	//max duljine nizova po stupcima
	for($i=0; $i < $size1 ;$i++){

		$max[] = max($rows[$i]) ; 
	}

	$sum = 0;  //ukupna šririna svih stupaca

	for($i=0; $i < $size1 ;$i++){

		$max[$i] = $max[$i] + 2;
		$sum = $sum + $max[$i];
	}
	//****************************************************

	//echo $sum;

	if ( $sum < 180 ) {

		$pdf->AddPage('P',array(200,300));
	}

	else  {
		//ako je širina veca - primijeni veličinu stranica na A3
		$pdf->AddPage('P','A3');
	}


	$header = $polje[0];  //zaglavlje-0.redak

	//pocetak kreiranja tablice
	$pdf->SetFillColor(176,0,0);   //boja zaglavlja
	$pdf->SetTextColor(255); //boja teksta

	$pdf->SetDrawColor(0); //rub
	$pdf->SetLineWidth(.5); //debljina ruba



	/*for($i=0;$i<3;$i++)
	   { 
			$pdf->Write(10,$max[$i] . ' ');
	   }*/



	//$w=array(18,20,18,20,20,20,25,20,20,20,25); //širina ćelija   version1
	//$w=array(40,40,50,35,40,40,50,30,40,40,50); //širina ćelija   version2

	//$pdf->Write(10,$header[3].' ');
	// ********************************************
	for($i=0; $i < $size1; $i++){

		$pdf->Cell($max[$i],10,$header[$i],1,0,'C',1); //zaglavlje
		//$pdf->Ln();


	}
	$pdf->Ln(); //novi red
	$pdf->SetFillColor(175); //boja ispune ćelije
	$pdf->SetTextColor(0); //crna boja
	$pdf->SetFont('');

	for($j=1; $j < $size; $j++){
		   for($k=0; $k <  $size1; $k++)
		{	
		    $pdf->Cell($max[$k],10,$polje[$j][$k],1,0,'L',1); 

			//$pdf->Write(10,$polje[$j][$k].' ');
		}
		$pdf->Ln(); 
	}


	//$pdf->Output();

	return $pdf;
    }
    
    public function xlsTest() {


	// kreiraj novi objekt
	$objPHPExcel = new \PHPExcel();


	// dodaj podatke

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
	//echo " Kreiraj .xlsx datoteku".'<br />';
	$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('xls/primjer.xlsx');
	//echo " Zapisano u  " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) .'<br />';


//	// spremanje u .xls format
//	echo " Kreiraj .xls datoteku".'<br />';
//	$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//	$objWriter->save('primjer');
//	echo " Zapisano u " , str_replace('.php', '.xls', pathinfo(__FILE__, PATHINFO_BASENAME)).'<br />';

	// kraj
	
	echo new \view\ShowXls(array(
	    "fileName" => './xls/primjer.xlsx'
	));

    }
    
    public function pdfTest() {
	$data = array(  array('Ime', 'Prezime', 'JMBAG','Grad','Ime', 'Prezime', 'JMBAG','Grad','Ime', 'Prezime', 'JMBAG'),
				array('Ivo4567890', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '001122334455'),
				array('Žurko', 'Žurković', '9988776655','Solin','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Đeneva','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Osijek','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Čakovec','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Zagreb','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Rijeka','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Županja','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Anastazija', 'Đurić', '2233447755','Ićiči','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Žurko', 'Žurković', '9988776655','Solin','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Đeneva','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Osijek','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Čakovec','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Zagreb','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Rijeka','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Županja','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Žurko', 'Žurković', '9988776655','Solin','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Đeneva','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Osijek','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Čakovec','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Zagreb','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Rijeka','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Županja','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Žurko', 'Žurković', '9988776655','Solin','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Đeneva','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Osijek','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Čakovec','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Zagreb','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Rijeka','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Županja','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Žurko', 'Žurković', '9988776655','Solin','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Đeneva','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Osijek','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Čakovec','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Zagreb','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Rijeka','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'),
				array('Jure', 'Đurić', '2233447755','Županja','Ivo', 'Ivić', '0011223344','Sisak','Ivo', 'Ivić', '0011223344'));
				
				
	    $objekt = $this->generatePdf($data);
	    
	    echo new \view\ShowPdf(array(
		"pdf" => $objekt
	    ));
    }

}