<?php

namespace view\ozsn;
use app\view\AbstractView;

class ObjavaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $objave;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
		if(count($this->objave))
		{
			
?>		
			<?php echo new \view\components\AddNewLink(array(
				"link" => \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'addObjava'
				)),
				"buttonText" => 'Dodaj novu objavu'
			)); ?>
			
			<?php echo new \view\components\DownloadLinks(array(
				"route" => \route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "displayObjava"
				))
			)); ?>

			<br><br>

			<div class="panel panel-default">
				<div class="panel-heading">Popis objava</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Autor</th>
						<th>Medij</th>
						<th>Datum</th>
						<th>Link</th>
						<th>Dokument</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			// Foreach activeObjava, generate row in table
			foreach($this->objave as $val)
			{
?>
					<tr>
						<td><?php echo $val->autorIme . ' ' . $val->autorPrezime; ?></td>
						<td><?php echo $val->nazivMedija; ?></td>
						<td><?php echo date('d.m.Y', strtotime($val->datumObjave)); ?></td>
						<td><?php if ($val->link) echo '<a href="'  . $val->link . '" target="_blank">Link</a>'; else echo '<i>Ne postoji</i>'; ?></td>
						<td>
							<?php if ($val->dokument) { ?>
							<a href="<?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'download'
							));?>?id=<?php echo $val->idObjave; ?>">Preuzmi</a>
							<?php } else echo '<i>Ne postoji</i>'; ?>
						</td>
						<td><a href="					
<?php
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyObjava'
				));
				echo '?id=' . $val->idObjave . '">Uredi</a> &nbsp; <a class="obrisiObjavu" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteObjava'
				));
				echo '?id=' . $val->idObjave . '">Obriši</a></td></tr>';
			}
			echo '</tbody></table></div>';
		}
		
		else
		{
			echo new \view\components\ErrorMessage(array(
				"errorMessage" => "Ne postoji niti jedna aktivna objava!"
			));
			
			echo new \view\components\AddNewLink(array(
				"link" => \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'addObjava'
				)),
				"buttonText" => 'Dodaj novu objavu'
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
    
    public function setObjave($objave) {
	$this->objave = $objave;
	return $this;
    }
}