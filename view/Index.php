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

<p>Ovaj link trebate sutnuti gore u traku (U main view) uz provjeru da samo admin to moze vidjet &nbsp;
    <a href="<?php echo \route\Route::get('d3')->generate(array(
        "controller" => 'administrator',
        "action" => 'addOzsn'
    ));?>">Link za dodavanje ozsna ako ste admin!</a>
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