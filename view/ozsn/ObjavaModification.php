<?php

namespace view\ozsn;
use app\view\AbstractView;

class ObjavaModification extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $mediji;
    private $elektrijade;
    private $objaveOElektrijadi;
    private $objava;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// VAZNO DODAJ NA AKCIJU OBRASCA GET PARAMETAR ID (od objave)
	// ispisi obrazac za mijenjanje/dodavanje objave
	// analogno kao kod sponzora (datoteka nek se zove polje "datoteka") (mogucnost brisanja i download-a datoteke)
	// pazi na enctype
	// elektrijade ispises kao dropdown list sa visestrukim izborom (oznaci vec one koje su prethodno oznacene)
	// Odabrane elektrijade vidis iz objaveOElektrijadi
	// mediji dropdown list (SAMO JEDAN SE MOZE IZABRATI) - oznaci ga ako je oznacen
	// ostali podaci su iz tablice objava
	// 
	// http://stackoverflow.com/questions/944158/php-multiple-dropdown-box-form-submit-to-mysql
	// Ovo ispod je samo nesh sto sam testirao izbrisi to
	
	?>
<form action="<?php echo \route\Route::get('d3')->generate(array(
    "controller" => "ozsn",
    "action" => "addObjava")); ?>" method="post">
  <select name="idElektrijade[]" multiple="multiple">
    <option value="1" >Option 1</option>
    <option value="2">naziv</option>
    <option value="Option 3">Option 3</option>
    <option value="Option 4">Option 4</option>
    <option value="Option 5">Option 5</option>
  </select>
  <input type="submit">
</form>
<?php
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
    public function setMediji($mediji) {
	$this->mediji = $mediji;
	return $this;
    }

    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }
    
    public function setObjaveOElektrijadi($objaveOElektrijadi) {
	$this->objaveOElektrijadi = $objaveOElektrijadi;
	return $this;
    }

    public function setObjava($objava) {
	$this->objava = $objava;
	return $this;
    }
}