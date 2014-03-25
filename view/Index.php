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
<h1>Pokušaj junačenja!</h1>

<p>Varijabla prenesena iz controllera: <br/>
    <?php echo $this->varijablaPrenesenaIzControllera;?></p>

<p>
            Ovo sam nabrzaka nesto sklepao, ko ima sucelje i bootstrap nek se poigra s ovim
            Nadalje sto se jos moze dodati:
            zaseban view (ili vise njih medju components) koji ce biti navigacija pa da se prikaze sa strane ili gore negdje
            u components dodati na isti nacin kao i ErrorMessage josh jedan recimo ResultMessage (koji bi bio plav a ne crven kao Error)
            
            Pogledajte kod za link(bitno da skuzite) :
            <a href="<?php echo \route\Route::get('d1')->generate(); ?>">Ovo je link na naslovnicu</a>
<!--            <a href="//<?php //echo \route\Route::get('d3')->generate(array(
//                "controller" => imeKontrollera PRVO SLOVO MALO,
//                "action" => akcija PRVO SLOVO MALO
//            )); ?>">Ovo bi bio link na akciju od nekog controllera</a>-->

ako umjesto d3 stavite d2 onda ne trebate pisati akciju nego samo kontroler te vas automatski preusmjeri na njegovu displaymetodu
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