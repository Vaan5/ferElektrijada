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
    
    /**
     * Opisuje prijenos varijable iz controllera ctl\Index
     * MORATE NAPRAVITI SETTER
     */
    private $varijablaPrenesenaIzControllera;
    
    protected function outputHTML() {
?>
<p><center><img src="./assets/img/naslovna.jpg" alt="FER logo" align="middle"></center></p>
<p>Ovaj link trebate sutnuti gore u traku (U main view) uz provjeru da samo admin to moze vidjet &nbsp;
    <a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'addOzsn'
    ));?>">Link za dodavanje ozsna ako ste admin! RIJEŠENO</a>
	
	<br><br>
	Test: <br>
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'changeProfile'
    ));?>">Link za uređivanje admin profila! STAVLJENO DA SE PRIKAŽE KAD JE ADMIN ON</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'addElektrijada'
    ));?>">Link za dodavanje elektrijade!</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'modifyElektrijada'
    ));?>?id=1">Link za uređivanje elektrijade!</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'displayElektrijada'
    ));?>">Link za prikaz elektrijada!</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'listOldOzsn'
    ));?>">Prikaži stare OZSN!</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'displayOzsn'
    ));?>?a=1">Lista aktivnih članova OSZN!</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'searchOzsn'
    ));?>">Pretraži aktivne članove OSZN!</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'modifyOzsn'
    ));?>?id=1">Uredi OZSN!</a>
	
	<br>
        
        <a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'searchPersons'
    ));?>">Pretraži osobe!</a>
	
	<br>
        
        <a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'displayPersons'
    ));?>">Pregledaj osobe!</a>
	
	<br><br>
	<b>OZSN</b>
	
	<br>
        
        <a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'addContact'
    ));?>">Dodavanje Kontakt Osobe</a>
        
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayAtribut'
    ));?>">DBM Atributi</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayVelMajice'
    ));?>">DBM Veličine majica</a>
</p>

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
    
    /**
     * 
     * @param mixed $varijablaPrenesenaIzControllera
     * @return \view\Index
     */
    public function setVarijablaPrenesenaIzControllera($varijablaPrenesenaIzControllera) {
        $this->varijablaPrenesenaIzControllera = $varijablaPrenesenaIzControllera;
        return $this;
    }
}
?>