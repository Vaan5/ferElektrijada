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
	
	<br><br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'addContact'
    )) . "";?>">Dodaj kontakt osobu</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'displayContacts'
    )) . "";?>">Lista kontakt osoba</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'ozsn',
        "action" => 'searchContacts'
    )) . "";?>">Pretraži kontakt osobe</a>
	
	
	</p>
	
	<br><br>
	
	<b>Ozsn NavBar</b>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'ozsn',
	    "action" => 'displayProfile'
	)) . "";?>">Profil</a>
	
	<br><br>
	
	<b>Sudionik NavBar</b>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'sudionik',
	    "action" => 'displayProfile'
	)) . "";?>">Profil</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'sudionik',
	    "action" => 'displayMyTeam'
	)) . "";?>">Moj Tim</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'sudionik',
	    "action" => 'displayOtherTeams'
	)) . "";?>">Ovogodišnji Timovi</a>
	
	<br><br>
	
	<b>Voditelj NavBar</b>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'voditelj',
	    "action" => 'displayProfile'
	)) . "";?>">Profil</a>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'voditelj',
	    "action" => 'displayPodrucja'
	)) . "";?>">Upravljanje Disciplinama</a>
	
	<br><br>
	
	<b>Izvještaji</b>
	
	<br>
        
        <a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'reportGenerator',
	    "action" => 'generateDisciplineList'
	));?>">Popis sudionika po disciplinama</a>
	
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
