<?php

namespace view\components;
use app\view\AbstractView;

class MediumPersonSearchForm extends AbstractView {
    /**
     *
     * @var string url of the script to handle this form data
     */
    private $postAction;
    
    /**
     *
     * @var string submit button text
     */
    private $submitButtonText;
	
	/**
     *
     * @var string show all button text
     */
    private $showAllButtonText;
	
	private $allController = "administrator";
	
	private $allAction = "displayPersons";
    
    protected function outputHTML() {
?>
    <form class="form-horizontal" role="form" action="<?php echo $this->postAction;?>" method="POST">
        <div class="form-group">
            <label for="ime" class="col-sm-3 control-label">Ime</label>
                        <div class="col-sm-8">
			<input type="text" name="ime" class="form-control" placeholder="Upišite ime" />
                        </div>
        </div>
        <div class="form-group">
            <label for="prezime" class="col-sm-3 control-label">Prezime</label>
                        <div class="col-sm-8">
			<input type="text" name="prezime" class="form-control" placeholder="Upišite prezime" />
                        </div>
        </div>
        <div class="form-group">
            <label for="korime" class="col-sm-3 control-label">Korisničko ime</label>
                        <div class="col-sm-8">
			<input type="text" name="ferId" class="form-control" placeholder="Upišite korisničko ime" />
                        </div>
        </div>
        <div class="form-group">
            <label for="jmbag" class="col-sm-3 control-label">JMBAG</label>
                        <div class="col-sm-8">
			<input type="text" name="JMBAG" class="form-control" placeholder="Upišite JMBAG" />
                        </div>
        </div>
        
        <div class="form-group">
            <label for="oib" class="col-sm-3 control-label">OIB</label>
                        <div class="col-sm-8">
			<input type="text" name="OIB" class="form-control" placeholder="Upišite OIB" />
                        </div>
        </div>
            <center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" />
				<a class="btn btn-primary" href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => $this->allController,
                    "action" => $this->allAction
                ));?>?a=1"><?php echo $this->showAllButtonText;?></a>
			</center>
    </form>

<?php

    }
    
    public function setPostAction($postAction) {
        $this->postAction = $postAction;
        return $this;
    }

    public function setSubmitButtonText($submitButtonText) {
        $this->submitButtonText = $submitButtonText;
        return $this;
    }
	
	public function setShowAllButtonText($showAllButtonText) {
        $this->showAllButtonText = $showAllButtonText;
        return $this;
    }
	
	public function setAllController($allController) {
		$this->allController = $allController;
		return $this;
	}

	public function setAllAction($allAction) {
		$this->allAction = $allAction;
		return $this;
	}
    
}