<?php

namespace view;
use app\view\AbstractView;

class Index extends AbstractView {
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    /**
     *
     * @var string 
     */
    private $resultMessage;
    
    protected function outputHTML() {
?>

<div class="main">
    <div class = "container-narrow">
    <?php echo new components\ErrorMessage(array(
        "errorMessage" => $this->errorMessage
    )); ?>
    </div>
</div>
        
<div class="main">
    <div class = "container-narrow">
    <?php echo new components\ResultMessage(array(
        "resultMessage" => $this->resultMessage
    )); ?>
    </div>
</div>

<p><center><img src="./assets/img/naslovna.jpg" alt="FER logo" align="middle"></center></p>
<p>
	
	<br><br>
	<b>OZSN</b>
	
	<br>
        
        <a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'addContact'
    ));?>">Dodavanje Kontakt Osobe</a>
	
	<br>
        
        <a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'searchContacts'
    ));?>">Pretraživanje Kontakt Osoba</a>
	
	<br>
        
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayAtribut'
    ));?>">DBM Atributi</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayVelMajice'
    ));?>">DBM Veličine majica</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayGodStud'
    ));?>">DBM Godine studija</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayNacinPromocije'
    ));?>">DBM Nacina promocije</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayKategorija'
    ));?>">DBM Kategorija sponzora</a>
		
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayZavod'
    ));?>">DBM Zavodi</a>
		
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayRadnoMjesto'
    ));?>">DBM Radna mjesta</a>
		
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displaySmjer'
    ));?>">DBM Smjerovi</a>
	
		
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'addSponzor'
    ));?>">Dodaj sponzora</a>
	
	<p>
	<b>Generator Pdfova</b>
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'reportGenerator',
        "action" => 'pdfTest'
    ));?>">pdf-ovi</a>
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'downloadLogo'
    ));?>">download</a>
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'reportGenerator',
        "action" => 'xlsTest'
    )) . "?type=xls";?>">excel_xls</a>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'reportGenerator',
        "action" => 'xlsTest'
    )) . "?type=xlsx";?>">excel_xlsx</a>
	</p>
</p>

<?php
    }
    
    /**
     * 
     * @param string $errorMessage
     * @return \view\Index
     */
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }
    
    /**
     * 
     * @param string $resultMessage
     * @return \view\Index
     */
    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
}
?>