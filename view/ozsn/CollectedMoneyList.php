<?php

namespace view\ozsn;
use app\view\AbstractView;

class CollectedMoneyList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $podrucja;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));	
		
?>
			<form class="form-horizontal" role="form" action="<?php echo \route\Route::get('d3')->generate(array(
				"controller" => "ozsn",
				"action" => "disciplineMoney"
					))?>" method="GET">
				
<?php
			if ($this->podrucja && count($this->podrucja)) {
?>
				<div class="form-group">	
					<label for="podrucja" class="col-sm-2 control-label">Discipline</label>
                                        <div class="col-sm-10">
						<select name="id" class="form-control">
                                                    <option value="">Odaberi...</option>

				<?php
						foreach($this->podrucja as $val)
						{
							echo '<option value="' . $val->idPodrucja . '"';
							echo '>' . $val->nazivPodrucja . '</option>';
						}
				?>					
                                                </select>
                                        </div>
				</div>
				
                            <center><input style="" type="submit" class="btn btn-primary" value="Prikupljeni novac za disciplinu" />
			    <a href="<?php echo \route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "displayMoneySum"
                                ))?>?x=1"><button type="button" class="btn btn-primary">Pregled prikupljenog novca</button></a></center>	
<?php 

						} else {
							echo new \view\components\ErrorMessage(array(
								"errorMessage" => "Ne postoji niti jedna disciplina!"
							));
						}
?>
                        
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
	
	public function setPodrucja($podrucja) {
		$this->podrucja = $podrucja;
		return $this;
	}
}
