<?php

namespace ctl;
use app\controller\Controller;
use \tFPDF;

class ReportGenerator implements Controller {
    
    private $errorMessage;
    private $resultMessage;
    
    private function checkRole() {
        // you must be logged in, and an Ozsn member with or without leadership
	$o = new \model\DBOsoba();
	if (!(\model\DBOsoba::isLoggedIn() && (\model\DBOsoba::getUserRole() === 'O' ||
		\model\DBOsoba::getUserRole() === 'OV') && $o->isActiveOzsn(session("auth")))) {
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

	

	if ( $sum < 180 ) {

		$pdf->AddPage('P',array(200,300));
	}

	else  {
		//ako je širina veca - primijeni veličinu stranica na A3
		$pdf->AddPage('P','A3');
	}


	$header = $polje[0];  //zaglavlje-0.redak

	//pocetak kreiranja tablice
	$pdf->SetFillColor(175);   //boja zaglavlja
	$pdf->SetTextColor(0); //boja teksta

	$pdf->SetDrawColor(0); //rub
	$pdf->SetLineWidth(.5); //debljina ruba



	// ********************************************
	for($i=0; $i < $size1; $i++){

		$pdf->Cell($max[$i],10,$header[$i],1,0,'C',1); //zaglavlje
		


	}
	$pdf->Ln(); //novi red
	$pdf->SetFillColor(255); //boja ispune ćelije
	$pdf->SetTextColor(0); //crna boja
	$pdf->SetFont('');

	for($j=1; $j < $size; $j++){
		   for($k=0; $k <  $size1; $k++)
		{	
		    $pdf->Cell($max[$k],10,$polje[$j][$k],1,0,'L',1); 

		
		}
		$pdf->Ln(); 
	}


	//$pdf->Output();

	return $pdf;
    }
    
    private function generateExcel(array $polje, $tip) {
	/** ukljuci biblioteku PHPExcel */ 
	require_once "lib/excel/Classes/PHPExcel.php";

	// kreiraj novi excel objekt
	$objPHPExcel = new \PHPExcel();

	$objPHPExcel->setActiveSheetIndex(0); //aktivni radni list
	$sheet = $objPHPExcel->getActiveSheet();
	$row = '1';
	$col = "A";

	foreach($polje as $row_cells) {
	    if(!is_array($row_cells)) { continue; }
		foreach($row_cells as $cell) {
		    $sheet->setCellValue($col.$row, $cell);
		    $col++;
				    $nd = $col; // nd pamti zadnji stupac
		}
	    $row += 1;
	    $col = "A";
	}

	//autosize širine ćelija

	//PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
	foreach(range('A',$nd) as $columnID) {
	    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
		->setAutoSize(true);
	}
	
	$putanja = 'xls/report_' . date("Y_m_d_H_i_s") . "." . $tip;

	if ($tip === 'xlsx') {
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	    $objWriter->save($putanja);
	} else if ($tip === 'xls') {
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	    $objWriter->save($putanja);
	}

	return $putanja;
    }
    
    public function xlsTest() {
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
	
	$putanja = null;
	if (get('type') === 'xlsx') {
	    $putanja = $this->generateExcel($data, 'xlsx');
	} else if (get('type') === 'xls') {
	    $putanja = $this->generateExcel($data, 'xls');
	}

	echo new \view\ShowXls(array(
	    "fileName" => './' . $putanja,
	    "tip" => get("type")
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
    
    /**
     * Generira popis sudionika po disciplinama
     */
    public function generateDisciplineList() {
	$this->checkRole();
	$this->checkMessages();
	
	$podrucje = new \model\DBPodrucje();
	$e = new \model\DBElektrijada();
	try {
	    $podrucja = $podrucje->getAll();
	    $elektrijade = $e->getAll();
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "reportGenerator",
                "action" => "generateDisciplineList"
            )) . "?msg=excep");
	}
	
	// if you have picked atributes which will be included in the report
	if (!postEmpty()) {
	    // check if they have selected the required fields
	    if (false === post("idPodrucja") || false === post("idElektrijade") || false === post("type")) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Izbor područja, Elektrijade i formata je obavezan!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "reportGenerator",
		    "action" => "generateDisciplineList"
		)) . "?msg=excep");
	    }
	    
	    // have they selected atleast one attribute
	    if (count($_POST) <= 3) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Odaberite barem jedan atribut!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "reportGenerator",
		    "action" => "generateDisciplineList"
		)) . "?msg=excep");
	    }
	    
	    // now proccess input data
	    try {
		$osoba = new \model\DBOsoba();
		$pov = $osoba->reportCompetitorList($_POST, post("idElektrijade"), post("idPodrucja"));
		
		$header = $this->decypherHeader();
		
		// now make array for generation function
		$array = array();
		$array[] = $header;
		if (count($pov)) {
		    foreach ($pov as $k => $v) {
			$h = array();
			foreach ($_POST as $kljuc => $vrijednost) {
			    if((strpos($kljuc, "id") === false || strpos($kljuc, "id") !== 0) && $kljuc !== 'type') {
				$h[] = $v->{$kljuc};
			    }
			}
			$array[] = $h;
		    }
		}
		
		switch (post("type")) {
		    case 'xls':
		    case 'xlsx':
			$putanja = $this->generateExcel($array, post("type"));
			echo new \view\ShowXls(array(
			    "fileName" => './' . $putanja,
			    "tip" => post("type")
			));
			break;
		    case 'pdf':
			$objekt = $this->generatePdf($array);
			echo new \view\ShowPdf(array(
			    "pdf" => $objekt
			));
			break;
		    default :
			break;
		}
		
		
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "reportGenerator",
		    "action" => "generateDisciplineList"
		)) . "?msg=excep");
	    }
	}
	
	echo new \view\Main(array(
	    "title" => "Popis Sudionika po Disciplinama",
	    "body" => new \view\reports\DisciplineCompetitorList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"podrucja" => $podrucja,
		"elektrijade" => $elektrijade
	    ))
	));
    }
    
    private function decypherHeader() {
	$a = array();
	foreach($_POST as $k => $v) {
	    if((strpos($k, "id") === false || strpos($k, "id") !== 0) && $k !== 'type') {
		switch($k) {
		    case 'ime':
			$a[] = "Ime";
			break;
		    case 'prezime':
			$a[] = "Prezime";
			break;
		    case 'mail':
			$a[] = "Email";
			break;
		    case 'brojMob':
			$a[] = "Mobitel";
			break;
		    case 'ferId':
			$a[] = "Korisničko ime";
			break;
		    case 'JMBAG':
			$a[] = "JMBAG";
			break;
		    case 'brOsobne':
			$a[] = "Osobna iskaznica";
			break;
		    case 'brPutovnice':
			$a[] = "Putovnica";
			break;
		    case 'osobnaVrijediDo':
			$a[] = "Osobna iskaznica vrijedi do";
			break;
		    case 'putovnicaVrijediDo':
			$a[] = "Putovnica vrijedi do";
			break;
		    case 'uloga':
			$a[] = "Uloga";
			break;
		    case 'MBG':
			$a[] = "Matični broj osiguranika";
			break;
		    case 'OIB':
			$a[] = "OIB";
			break;
		    case 'nazivAtributa':
			$a[] = "Atribut";
			break;
		    case 'velicina':
			$a[] = "Majica";
			break;
		    case 'studij':
			$a[] = "Studij";
			break;
		    case 'godina':
			$a[] = "Godina";
			break;
		    case 'nazivSmjera':
			$a[] = "Smjer";
			break;
		    case 'nazivZavoda':
			$a[] = "Zavod";
			break;
		    case 'skraceniNaziv':
			$a[] = "Zavod";
			break;
		    case 'naziv':
			$a[] = "Radno mjesto";
			break;
		    case 'brojBusa':
			$a[] = "Bus";
			break;
		    case 'brojSjedala':
			$a[] = "Sjedalo";
			break;
		    case 'napomena':
			$a[] = "Napomena";
			break;
		    case 'tip':
			$a[] = "Student/Djelatnik";
			break;
		    case 'rezultatPojedinacni':
			$a[] = "Rezultat";
			break;
		    case 'ukupanBrojSudionika':
			$a[] = "Ukupno sudionika";
			break;
		    default:
			break;		    
		}
	    }
	}
	return $a;
    }

}