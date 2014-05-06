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
	
	<b>Sudionik NavBar</b>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'sudionik',
	    "action" => 'displayProfile'
	));?>">Profil</a>
	
	
	<br><br>
	
	<b>Voditelj NavBar</b>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'voditelj',
	    "action" => 'displayProfile'
	));?>">Profil</a>
	
	<br><br>
	
	<b>Busevi Link -> Randy stavi u ozsn navbar</b>
	
	<br>
	
	<a href="<?php echo \route\Route::get('d3')->generate(array(
	    "controller" => 'busevi',
	    "action" => 'display'
	));?>">Raspored po Autobusima</a>
	
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
