<?php

namespace view\components;
use app\view\AbstractView;

class ElekPodForm extends AbstractView {

    private $postAction;
    private $submitButtonText;
    private $elekPod;
	private $controller;
	private $action;
	private $idPodrucja;
    
    protected function outputHTML() {
?>
    <form class="form-horizontal" role="form" action="<?php echo $this->postAction;?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="rezultatGrupni" class="col-sm-4 control-label">Rezultat</label>
                        <div class="col-sm-5">
                        <input type="text" name="rezultatGrupni" class="form-control" placeholder="Upišite rezultat" <?php if($this->elekPod && $this->elekPod->rezultatGrupni){ echo 'value="' . $this->elekPod->rezultatGrupni . '"'; } ?> />
                        </div>
        </div>
        <div class="form-group">
            <label for="ukupnoEkipa" class="col-sm-4 control-label">Ukupni broj ekipa</label>
                        <div class="col-sm-5">           
                        <input type="text" name="ukupanBrojEkipa" class="form-control" placeholder="Upišite broj ekipa" <?php if($this->elekPod && $this->elekPod->ukupanBrojEkipa){ echo 'value="' . $this->elekPod->ukupanBrojEkipa . '"'; } ?> />
                        </div>
        </div>
<?php		
		if($this->elekPod && $this->elekPod->slikaLink)
		{
?>
		
        <div class="form-group">        
        <label for="preuzmi" class="col-sm-3 control-label"></label>
            <div class="col-sm-9">
            <a href="<?php echo \route\Route::get('d3')->generate(array(
				"controller" => $this->controller,
				"action" => $this->action
		));?>?id=<?php echo $this->elekPod->idElekPodrucje; ?>">Preuzmi sliku &nbsp;</a>
            <input type="checkbox" name="delete"> Obriši sliku    
            </div>
        </div>
		
<?php				
		}

		else
		{
?>              <div class="form-group">
		<label for="slika" class="col-sm-4 control-label">Slika</label>
		<div class="col-sm-8">
                <input type="file" style="margin-top: 7px" name="datoteka" />
                </div>
                </div>
<?php
		}
?>		
		<?php if($this->elekPod && $this->elekPod->idElekPodrucje){ echo '<input type="hidden" name="idElekPodrucje" value="' . $this->elekPod->idElekPodrucje . '">'; } ?>
		<?php if($this->idPodrucja){ echo '<input type="hidden" name="idPodrucja" value="' . $this->idPodrucja . '">'; } ?>
        <center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" /></center>
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
    
    public function setElekPod($elekPod) {
		$this->elekPod = $elekPod;
		return $this;
	}
	
	public function setController($controller) {
		$this->controller = $controller;
		return $this;
	}

	public function setAction($action) {
		$this->action = $action;
		return $this;
	}
		
	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}
}
