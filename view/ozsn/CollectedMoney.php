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
	private $ostalo;
	private $korijeni;
	private $g;
    
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
						<td><?php echo $val[0]; ?></td>
						<td><?php echo isset($val[1]) ? $val[1] : 0; ?></td>
					</tr>
<?php
			}
			
			if ($this->korijeni && count($this->korijeni)) {
				foreach ($this->korijeni as $k) {
					echo "<tr>
							<td>" . $k->nazivPodrucja . "</td>
							<td>";
					
					$naziv = null;
					if ($this->g && count($this->g)) {
						foreach ($this->g as $v) {
							if ($v->nazivPodrucja === $k->nazivPodrucja) {
								$naziv = $v->suma;
								break;
							}
						}
					}
					
					echo ($naziv === null ? 0 : $naziv) . "</td></tr>";
				}
			}
?>
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
	
	public function setOstalo($ostalo) {
		$this->ostalo = $ostalo;
		return $this;
	}
	
	public function setKorijeni($korijeni) {
		$this->korijeni = $korijeni;
		return $this;
	}

	public function setG($g) {
		$this->g = $g;
		return $this;
	}

}
