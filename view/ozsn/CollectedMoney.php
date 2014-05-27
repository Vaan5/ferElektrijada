<?php

namespace view\ozsn;
use app\view\AbstractView;

class CollectedMoney extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $podrucja;
	private $ukupno;
	private $znanje;
	private $sport;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));	
		
		if(count($this->podrucja))
		{
			
			echo new \view\components\DownloadLinks(array(
				"route" => \route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "displayMoneySum"
				)) . "?x=sss",
				"onlyParam" => false
			));			
?>
			<br><br>
			
			<div class="panel panel-default">
				<div class="panel-heading">Prikupljena Sredstva</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Područje</th>
						<th>Prikupljeno</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			// Foreach activeObjava, generate row in table
			foreach($this->podrucja as $val)
			{
?>
					<tr>
						<td><?php echo $val->nazivPodrucja; ?></td>
						<td><?php echo $val->suma === null ? 0 : $val->suma; ?></td>
					</tr>
<?php
			}
?>
					<tr>
						<td>Znanje</td>
						<td><?php echo $this->znanje[0]->suma === null ? 0 : $this->znanje[0]->suma;?></td>
					</tr>
					<tr>
						<td>Sport</td>
						<td><?php echo $this->sport[0]->suma === null ? 0 : $this->sport[0]->suma;?></td>
					</tr>
					<tr>
						<td>Ukupno</td>
						<td><?php echo $this->ukupno === null ? 0 : $this->ukupno;?></td>
					</tr>
<?php
			echo '</tbody></table></div>';
		}
		 else
		{
			echo new \view\components\ErrorMessage(array(
				"errorMessage" => "Ne postoji niti jedna disciplina!"
			));
		}
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
	
	public function setUkupno($ukupno) {
		$this->ukupno = $ukupno;
		return $this;
	}

	public function setZnanje($znanje) {
		$this->znanje = $znanje;
		return $this;
	}

	public function setSport($sport) {
		$this->sport = $sport;
		return $this;
	}

}
