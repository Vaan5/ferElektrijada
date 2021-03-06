<?php

namespace model\reports;
use app\model\Model;

class ReportModel implements Model {
    
    public function generatePdf(array $polje) {
	require_once "lib/tfpdf/tfpdf.php";
	
	$pdf = new \tFPDF('P','mm',array(200,300));

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
    
    public function generateExcel(array $polje, $tip) {
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
    
}
