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
	if (!((\model\DBOsoba::isLoggedIn() && (\model\DBOsoba::getUserRole() === 'O' ||
		\model\DBOsoba::getUserRole() === 'OV') && $o->isActiveOzsn(session("auth"))) || (\model\DBOsoba::isLoggedIn() && \model\DBOsoba::getUserRole() === 'A' ))) {
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
    
    /**
     * Generira popis sudionika po disciplinama
     */
    public function generateDisciplineList() {
	$this->checkRole();
	$this->checkMessages();
	
	$podrucje = new \model\DBPodrucje(); //padajuci izbornici
	$e = new \model\DBElektrijada();
	
	
	try {
	    $podrucja = $podrucje->getAllWithRoots();  //dohvati sve iz tablice podrucja
		$elektrijade = $e->getAll();   //koristi se pri kraju, treba nam za poglede
		
		
			
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "reportGenerator",
                "action" => "generateDisciplineList"
            )) . "?msg=excep");
	}
	
	// if you have picked atributes which will be included in the report
	if (!postEmpty())
	 {
	    // check if they have selected the required fields
	    if (false === post("idPodrucja") || false === post("idElektrijade") || false === post("type"))
		
		 {
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
		$osoba = new \model\DBOsoba(); //objekt , treba nam samo radi poziva reportCompetitorList
		
		
		
		$temp = array();
		$polje = array();
		



		
		$pov = array();
		//brojimo vršna područja - ona koja nemaju nadređenih
		for($i=0; $i < count($podrucja); $i++){
			if( $podrucja[$i]->idNadredjenog === NULL)
			   $polje[] = $podrucja[$i]->idPodrucja;
		}
	     
		  
	      for($z=0; $z < count($polje); $z++){
			  
			  //ako je odabrano vršno područje
			if( post("idPodrucja") == $polje[$z]){
			  
				
				//pronađi podređena područja
			for($j=0; $j < count($polje); $j++){
			for($i=0; $i < count($podrucja); $i++){
				if ( $polje[$j] == $podrucja[$i]->idNadredjenog)
			
			$temp[$j][] = $podrucja[$i]->idPodrucja;
			
		  
			}}
			
		//zvoi funkciju iz modela sa idPodrucja jednakim svim id-ima podređenih područja
		for($i=0; $i < count($temp[$z]); $i++){
			$niz[] = $osoba->reportCompetitorList($_POST, post("idElektrijade"), $temp[$z][$i]);
		}
	
		 //pretvorba 2d polja u 1d zbog funkcije
		   for ($i = 0; $i < count($niz); $i++) {
      for ($j = 0; $j < count($niz[$i]); $j++) {
        $pov[] = $niz[$i][$j];
		
      }}
	   break;
	   
		}
		//ako nisu odabrana vršna područja
		else if( post("idPodrucja") != $polje[$z]){
			$pov = $osoba->reportCompetitorList($_POST, post("idElektrijade"), post("idPodrucja")); //poziv metode nad objektom
			
			}
		
			
		
			}
		
		
		
			
			
			
	
		
	
			
			
			
		
		
	
		
		//ljepsi nazivi vrijednosti atributa
		if( post('uloga') ){
		foreach( $pov as $k => $v)
		{
			
			if ( $v->uloga == 'O' ) $v->uloga = 'OZSN';
			else if ( $v->uloga == 'S' ) $v->uloga = 'Sudionik';
			else if ( $v->uloga == 'A' ) $v->uloga = 'Admin';
		}}
		
		if ( post('tip') )
		{	foreach( $pov as $k => $v)
		{
			if ( $v->tip == 'D' ) $v->tip = 'Djelatnik';
			else if ( $v->tip == 'S' ) $v->tip = 'Student';
			else if ( $v->tip == 'O' ) $v->tip = 'OZSN';
		}}
		
		if ( post('vrstaPodrucja') )
		{	foreach( $pov as $k => $v)
		{
			if ( $v->vrstaPodrucja === 0 ) $v->vrstaPodrucja = 'Individualno';
			else if ( $v->vrstaPodrucja === 1 ) $v->vrstaPodrucja = 'Timsko';
		}}
		
		if ( post('aktivanDokument') )
		{	foreach( $pov as $k => $v)
		{
			if ( $v->aktivanDokument == '0' ) $v->aktivanDokument = 'putovnica';
			else if ( $v->aktivanDokument == '1' ) $v->aktivanDokument = 'osobna';
			
		}}
			
			
			
			
	
		
	
		
		
		$header = $this->decypherHeader();
		
		
		
		// now make array for generation function
		$array = array();
		$array[] = $header;  //dodajemo zaglavlje
		
		if (count($pov)) {
		    foreach ($pov as $k => $v) {
				
		
				// $v je objekt
			$h = array();
			foreach ($_POST as $kljuc => $vrijednost) 
			{
				//za sve iz $_POST OSIM za idPodrucja, idElektrijade i type
			    if((strpos($kljuc, "id") === false || strpos($kljuc, "id") !== 0) && $kljuc !== 'type') {
				$h[] = $v->{$kljuc};
			    }
			}
			$array[] = $h;
			
			
		    }
			
		}

		
		$reportModel = new \model\reports\ReportModel();  //novi objekt
		switch (post("type")) {
		    case 'xls':
		    case 'xlsx':
			$putanja = $reportModel->generateExcel($array, post("type"));
			echo new \view\ShowXls(array(
			    "fileName" => './' . $putanja,
			    "tip" => post("type")
			));
			break;
		    case 'pdf':
			$objekt = $reportModel->generatePdf($array);
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
	
	  /**
     * Generira popis majica po veličini i spolu
     */
    public function generateTshirtsList() {
	$this->checkRole();
	$this->checkMessages();
	
	$e = new \model\DBElektrijada();
	
	try {
	    
		$elektrijade = $e->getAll();   //koristi se pri kraju, treba nam za poglede, polje objekata
		
		
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "reportGenerator",
                "action" => "generateTshirtsList"
            )) . "?msg=excep");
	}
	
	// if you have picked atributes which will be included in the report
	if (!postEmpty()) {
	    // check if they have selected the required fields
	    if (false === post("idOpcija")|| false === post("idElektrijade") || false === post("type")) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Izbor opcije, Elektrijade i formata je obavezan!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "reportGenerator",
		    "action" => "generateTshirtsList"
		)) . "?msg=excep");
	    }
	    
	   
	    
	    // now proccess input data
	    try {
		$osoba = new \model\DBOsoba(); //objekt jer se tamo nalazi funkcija reportTshirtList
		$pov = $osoba->reportTshirtList(post("idElektrijade"), post("idOpcija")); //poziv metode nad objektom
		
		
		
		switch(post("idOpcija")){
			case '0':
		    $header = array('Spol osobe', 'Broj majica');
			$pom = array('spol', 'brojMajica');	
			
			//ljepsi nazivi vrijednosti atributa
			foreach( $pov as $key => $value){
			if( $value->spol == 'M') $value->spol = 'Muški';
			else if( $value->spol == 'Ž') $value->spol = 'Ženski';
		}
			
			break;
			
			case '1':
			$header = array('Veličina majice', 'Broj majica');
			$pom = array('velicina', 'brojMajica');
			break;
			
			case '2':
			$header = array('Veličina majice', 'Spol osobe', 'Broj majica');
			$pom = array('velicina', 'spol', 'brojMajica');
			
			//ljepsi nazivi vrijednosti atributa
			foreach( $pov as $key => $value){
			if( $value->spol == 'M') $value->spol = 'Muški';
			else if( $value->spol == 'Ž') $value->spol = 'Ženski';
		}
			break;
			
		}
		
		// now make array for generation function
		$array = array();
		$array[] = $header;  //dodajemo zaglavlje
		
		
		if (count($pov)) {
		    foreach ($pov as $k => $v) {
				// $v je objekt
			$h = array();
			foreach ($pom as $kljuc => $vrijednost) {
				//za sve iz $_POST OSIM za idPodrucja, idElektrijade i type
			    
				$h[] = $v->{$vrijednost};
			    
			}
			$array[] = $h;
			
		    }
			
		}
		
		$reportModel = new \model\reports\ReportModel();  //novi objekt
		switch (post("type")) {
		    case 'xls':
		    case 'xlsx':
			$putanja = $reportModel->generateExcel($array, post("type"));
			echo new \view\ShowXls(array(
			    "fileName" => './' . $putanja,
			    "tip" => post("type")
			));
			break;
		    case 'pdf':
			$objekt = $reportModel->generatePdf($array);
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
		    "action" => "generateTshirtsList"
		)) . "?msg=excep");
	    }
	}
	
	echo new \view\Main(array(
	    "title" => "Popis Majica po spolu i veličinama",
	    "body" => new \view\reports\TshirtsList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"elektrijade" => $elektrijade
		
	    ))
	));
    }
	
		
	  /**
     * Generira statistiku za godinu i smjer
     */
    public function generateYearModuleStatisticsList() {
	$this->checkRole();
	$this->checkMessages();
	
	//$velmajice = new \model\DBVelMajice(); //padajuci izbornici
	$e = new \model\DBElektrijada();
	//$o = new \model\DBOsoba();
	
	
	try {
	    //$velmajica = $velmajice->getAll();  //dohvati sve iz tablice podrucja
		$elektrijade = $e->getAll();   //koristi se pri kraju, treba nam za poglede, polje objekata
		//$osobe = $o->getAllPersons();
		
		
		
		
		
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "reportGenerator",
                "action" => "generateYearModuleStatisticsList"
            )) . "?msg=excep");
	}
	
	// if you have picked atributes which will be included in the report
	if (!postEmpty()) {
	    // check if they have selected the required fields
	    if (false === post("idOpcija")|| false === post("idElektrijade") || false === post("type")) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Izbor opcije, Elektrijade i formata je obavezan!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "reportGenerator",
		    "action" => "generateYearModuleStatisticsList"
		)) . "?msg=excep");
	    }
	    
	    // now proccess input data
	    try {
		$osoba = new \model\DBOsoba(); //objekt jer se tamo nalazi funkcija reportTshirtList
		$pov = $osoba->reportYearModuleStatisticsList(post("idElektrijade"), post("idOpcija")); //poziv metode nad objektom
		//pov je polje objekata
		
		switch(post("idOpcija")){
			case '0':
		    $header = array('Godina studija', 'Broj studenata');
			$pom = array('godina', 'brojStudenata');		
			break;
			
			case '1':
			$header = array('Smjer studija', 'Broj studenata');
			$pom = array('nazivSmjera', 'brojStudenata');
			break;
			
			case '2':
			$header = array('Godina studija', 'Smjer studija', 'Broj studenata');
			$pom = array('godina', 'nazivSmjera', 'brojStudenata');
			break;
			
		}
		
		// now make array for generation function
		$array = array();
		$array[] = $header;  //dodajemo zaglavlje
		
		
		if (count($pov)) {
		    foreach ($pov as $k => $v) {
				// $v je objekt
			$h = array();
			foreach ($pom as $kljuc => $vrijednost) {
				//za sve iz $_POST OSIM za idPodrucja, idElektrijade i type
			    
				$h[] = $v->{$vrijednost};
			}
			$array[] = $h;
			
		    }
			
		}

		$reportModel = new \model\reports\ReportModel();  //novi objekt
		switch (post("type")) {
		    case 'xls':
		    case 'xlsx':
			$putanja = $reportModel->generateExcel($array, post("type"));
			echo new \view\ShowXls(array(
			    "fileName" => './' . $putanja,
			    "tip" => post("type")
			));
			break;
		    case 'pdf':
			$objekt = $reportModel->generatePdf($array);
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
		    "action" => "generateYearModuleStatisticsList"
		)) . "?msg=excep");
	    }
	}
	
	echo new \view\Main(array(
	    "title" => "Statistika po godinama i smjeru",
	    "body" => new \view\reports\YearModuleStatisticsList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"elektrijade" => $elektrijade
		
	    ))
	));
    }
	
	    /**
     * Generira popis sudionika po godini i smjeru
     */
    public function generateYearModuleCompetitorsList() {
	$this->checkRole();
	$this->checkMessages();
	
	$e = new \model\DBElektrijada();
	
	try {
	   
		$elektrijade = $e->getAll();   //koristi se pri kraju, treba nam za poglede
		 
		
		
		
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "reportGenerator",
                "action" => "generateYearModuleCompetitorsList"
            )) . "?msg=excep");
	}
	
	// if you have picked atributes which will be included in the report
	if (!postEmpty())
	 {
	    // check if they have selected the required fields
	    if (false === post("idOpcija") || false === post("idElektrijade") || false === post("type"))
		
		 {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Izbor opcije, Elektrijade i formata je obavezan!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "reportGenerator",
		    "action" => "generateYearModuleCompetitorsList"
		)) . "?msg=excep");
	    }
	  
	    // have they selected atleast one attribute
	    if (count($_POST) <= 3) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Odaberite barem jedan atribut!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "reportGenerator",
		    "action" => "generateYearModuleCompetitorsList"
		)) . "?msg=excep");
	    }
	    
	    // now proccess input data
	    try {
		$osoba = new \model\DBOsoba(); //objekt , treba nam samo radi poziva reportCompetitorList
		$pov = $osoba->reportYearModuleCompetitorsList($_POST, post("idElektrijade"), post("idOpcija")); 
		
		
		//ljepsi nazivi vrijednosti atributa
		if( post('uloga') ){
		foreach( $pov as $k => $v)
		{
			
			if ( $v->uloga == 'O' ) $v->uloga = 'OZSN';
			else if ( $v->uloga == 'S' ) $v->uloga = 'Sudionik';
			else if ( $v->uloga == 'A' ) $v->uloga = 'Admin';
		}}
		
		if ( post('tip') )
		{	foreach( $pov as $k => $v)
		{
			if ( $v->tip == 'D' ) $v->tip = 'Djelatnik';
			else if ( $v->tip == 'S' ) $v->tip = 'Student';
			else if ( $v->tip == 'O' ) $v->tip = 'OZSN';
		}}
		
		if ( post('aktivanDokument') )
		{	foreach( $pov as $k => $v)
		{
			if ( $v->aktivanDokument == '0' ) $v->aktivanDokument = 'putovnica';
			else if ( $v->aktivanDokument == '1' ) $v->aktivanDokument = 'osobna';
			
		}}
		
		if ( post('vrstaPodrucja') )
		{	foreach( $pov as $k => $v)
		{
			if ( $v->vrstaPodrucja === '0' ) $v->vrstaPodrucja = 'Individualno';
			else if ( $v->vrstaPodrucja === '1' )$v->vrstaPodrucja = 'Timsko';
		}}
			
			
		
		$header = $this->decypherHeader();
		
		if(post('idOpcija') == '0') { $pom = array('Godina');  }
		else if(post('idOpcija') == '1') { $pom = array('Smjer');  }
		else if(post('idOpcija') == '2')
		{
			  $pom = array('Godina','Smjer');

		}
		array_splice($header,0,0,$pom); 	
		
		
		// now make array for generation function
		$array = array();
		$array[] = $header;  //dodajemo zaglavlje
		
		
		if (count($pov)) {
		    foreach ($pov as $k => $v) {
	
			$h = array();
			foreach ($_POST as $kljuc => $vrijednost) 
			{
				//za sve iz $_POST OSIM za idOpcija, idElektrijade i type
			    if((strpos($kljuc, "id") === false || strpos($kljuc, "id") !== 0) && $kljuc !== 'type') {
					
				$h[] = $v->{$kljuc};
				

				
			    }
			}
			if( post('idOpcija') == '0') { $buf = array($v->{'godina'}); }
			
			else if( post('idOpcija') == '1') { $buf = array($v->{'nazivSmjera'}); }
			
			else if( post('idOpcija') == '2') { $buf = array($v->{'godina'}, $v->{'nazivSmjera'}); }
			
			array_splice($h,0,0,$buf);
			

				 $array[] = $h;
			 }
			
		}
			

		
		$reportModel = new \model\reports\ReportModel();  //novi objekt
		switch (post("type")) {
		    case 'xls':
		    case 'xlsx':
			$putanja = $reportModel->generateExcel($array, post("type"));
			echo new \view\ShowXls(array(
			    "fileName" => './' . $putanja,
			    "tip" => post("type")
			));
			break;
		    case 'pdf':
			$objekt = $reportModel->generatePdf($array);
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
		    "action" => "generateYearModuleCompetitorsList"
		)) . "?msg=excep");
	    }
	}
	
	echo new \view\Main(array(
	    "title" => "Popis sudionika po godini i smjeru",
	    "body" => new \view\reports\YearModuleCompetitorsList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"elektrijade" => $elektrijade
	    ))
	));
    }

	    /**
     * Generira popis sudionika po autobusima
     */
    public function generateBusCompetitorsList() {
	$this->checkRole();
	$this->checkMessages();
	
	$e = new \model\DBElektrijada();
	
	try {
	   
		$elektrijade = $e->getAll();   //koristi se pri kraju, treba nam za poglede
		 
		
		
		
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "reportGenerator",
                "action" => "generateBusCompetitorsList"
            )) . "?msg=excep");
	}
	
	// if you have picked atributes which will be included in the report
	if (!postEmpty())
	 {
	    // check if they have selected the required fields
	    if (false === post("idElektrijade") || false === post("type"))
		
		 {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Izbor Elektrijade i formata je obavezan!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "reportGenerator",
		    "action" => "generateBusCompetitorsList"
		)) . "?msg=excep");
	    }
	  
	    // have they selected atleast one attribute
	    if (count($_POST) <= 2) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Odaberite barem jedan atribut!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "reportGenerator",
		    "action" => "generateBusCompetitorsList"
		)) . "?msg=excep");
	    }
		
	    // now proccess input data
	    try {
		$osoba = new \model\DBOsoba(); //objekt , treba nam samo radi poziva reportCompetitorList
		$pov = $osoba->reportBusCompetitorsList($_POST, post("idElektrijade")); 
		
		
		
		
		//ljepsi nazivi vrijednosti atributa
		if( post('uloga') ){
		foreach( $pov as $k => $v)
		{
			
			if ( $v->uloga == 'O' ) $v->uloga = 'OZSN';
			else if ( $v->uloga == 'S' ) $v->uloga = 'Sudionik';
			else if ( $v->uloga == 'A' ) $v->uloga = 'Admin';
		}}
		
		if ( post('tip') )
		{	foreach( $pov as $k => $v)
		{
			if ( $v->tip == 'D' ) $v->tip = 'Djelatnik';
			else if ( $v->tip == 'S' ) $v->tip = 'Student';
			else if ( $v->tip == 'O' ) $v->tip = 'OZSN';
		}}
		
		if ( post('aktivanDokument') )
		{	foreach( $pov as $k => $v)
		{
			if ( $v->aktivanDokument == '0' ) $v->aktivanDokument = 'putovnica';
			else if ( $v->aktivanDokument == '1' ) $v->aktivanDokument = 'osobna';
			
		}}
			
			
		
		$header = $this->decypherHeader();
		
		
		$pom = array('Redni broj busa', 'Naziv grupe', 'Polazak', 'Povratak', 'Napomena', 'Broj sjedala');  
		array_splice($header,0,0,$pom); 	
		
		
		// now make array for generation function
		$array = array();
		$array[] = $header;  //dodajemo zaglavlje
		
		
		if (count($pov)) {
		    foreach ($pov as $k => $v) {
	
			$h = array();
			foreach ($_POST as $kljuc => $vrijednost) 
			{
				//za sve iz $_POST OSIM za idOpcija, idElektrijade i type
			    if((strpos($kljuc, "id") === false || strpos($kljuc, "id") !== 0) && $kljuc !== 'type') {
					
				$h[] = $v->{$kljuc};
				

				
			    }
			}
			$buf = array($v->{'brojBusa'}, $v->{'nazivGrupe'}, $v->{'polazak'}, $v->{'povratak'}, $v->{'napomena'}, $v->{'brojSjedala'}); 
			array_splice($h,0,0,$buf);
			

				 $array[] = $h;
			 }
			
		}
			

		
		$reportModel = new \model\reports\ReportModel();  //novi objekt
		switch (post("type")) {
		    case 'xls':
		    case 'xlsx':
			$putanja = $reportModel->generateExcel($array, post("type"));
			echo new \view\ShowXls(array(
			    "fileName" => './' . $putanja,
			    "tip" => post("type")
			));
			break;
		    case 'pdf':
			$objekt = $reportModel->generatePdf($array);
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
		    "action" => "generateBusCompetitorsList"
		)) . "?msg=excep");
	    }
	}
	
	echo new \view\Main(array(
	    "title" => "Popis sudionika po autobusima",
	    "body" => new \view\reports\BusCompetitorsList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"elektrijade" => $elektrijade
	    ))
	));
    }
	
    
    private function decypherHeader() {
	$a = array();
	foreach($_POST as $k => $v) {
		//za sve iz $_POST OSIM za idPodrucja, idElektrijade i type
	    if( (strpos($k, "id") === false || strpos($k, "id") !== 0) && $k !== 'type' ) {
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
			case 'aktivanDokument':
			$a[] = "Aktivan dokument";
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
			case 'vrstaPodrucja':
			$a[] = "Individualno/Timsko";
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