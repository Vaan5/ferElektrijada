<?php

namespace view\reports\forms;
use app\view\AbstractView;

class TshirtsForm extends AbstractView {
    private $route;
    private $submitButtonText;
    private $elektrijade;
    
    protected function outputHTML() {
?>
     <form class="form-inline" role="form" method="post" action="<?php echo $this->route;?>">
        <center><div class="form-group">
        <label class="sr-only" for="opcija">Opcija</label>
            <select name="idOpcija" class="form-control">
			<option value="">Odaberi mogućnost</option>
<?php
		$option = array( 'Po spolu', 'Po veličini', 'Po spolu i veličini');
		foreach($option as $k => $v)
		{	
			echo '<option value="' . $k . '"';
			echo '>' . $v . '</option>';
		}
?>					
        </select>	
        </div>
	<div class="form-group">		
        <label class="sr-only" for="elektrijada">Elektrijada</label>
            <select name="idElektrijade" class="form-control">
			<option value="">Odaberi elektrijadu</option>
<?php
		foreach($this->elektrijade as $val)
		{
			echo '<option value="' . $val->idElektrijade . '"';
			echo '>' . $val->mjestoOdrzavanja . " " . $val->datumPocetka . '</option>';
		}
?>					
        </select>
        </div></center><br/><br/>
        
    
        

      

	<?php echo new DownloadOptionsForm();?>	
	
        <center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText; ?>" /></center>
    </form>
<?php
    }
    
    public function setRoute($route) {
	$this->route = $route;
	return $this;
    }

    public function setSubmitButtonText($submitButtonText) {
	$this->submitButtonText = $submitButtonText;
	return $this;
    }
    

    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }
	

}