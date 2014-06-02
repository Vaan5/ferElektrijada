<?php

namespace view\components;
use app\view\AbstractView;

class ContactSearchForm extends AbstractView {
	
	private $postAction;
    private $submitButtonText;
    private $kontakti;
    private $tvrtke;
    private $sponzori;
    private $mediji;
    
    protected function outputHTML() {

	// Samo jedno text polje s name="search"
	// + ispisi drop down liste tvrtki sponzora i medija (name je idSponzora, idTvrtke i slicno) a value je konkretni id, + dodaj polje s value ""
	//
		
?>
	<form class="form-horizontal" role="form" method="post" action="<?php echo $this->postAction;?>">
		<div class="form-group">
			<label for="search" class="col-sm-3 control-label">Pretraga</label>
			<div class="col-sm-9">
				<input type="text" name="search" class="form-control" placeholder="Upišite pojam pretrage" />
			</div>
		</div>
		
		<div class="form-group">
			<label for="tvrtka" class="col-sm-3 control-label">Tvrtka</label>
			<div class="col-sm-9">
				<select name="idTvrtke" class="form-control">
					<option selected="selected" value="">Odaberi...</option>

<?php
		foreach($this->tvrtke as $val)
		{
			echo '<option value="' . $val->idTvrtke . '">' . $val->imeTvrtke . '</option>';
		}
?>					
				</select>
			</div>
        </div>
		
		<div class="form-group">
			<label for="sponzor" class="col-sm-3 control-label">Sponzor</label>
			<div class="col-sm-9">
				<select name="idSponzora" class="form-control">
					<option selected="selected" value="">Odaberi...</option>

<?php
		foreach($this->sponzori as $val)
		{
			echo '<option value="' . $val->idSponzora . '">' . $val->imeTvrtke . '</option>';
		}
?>					
				</select>
			</div>
        </div>
		
		<div class="form-group">
			<label for="medij" class="col-sm-3 control-label">Medij</label>
			<div class="col-sm-9">
				<select name="idMedija" class="form-control">
					<option selected="selected" value="">Odaberi...</option>

<?php
		foreach($this->mediji as $val)
		{
			echo '<option value="' . $val->idMedija . '">' . $val->nazivMedija . '</option>';
		}
?>					
				</select>
			</div>
        </div>
		
		<center>
			<input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText; ?>" />
			<button type="button" class="btn btn-primary" onClick="javascript:location.href = '<?php echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'displayContacts'
				)) . "";?>';">Prikaži sve kontakt osobe</button>
			
			<button type="button" class="btn btn-primary" onClick="javascript:location.href = '<?php echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'addContact'
				));?>';">Dodaj kontakt osobu</button></a>
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
	
	public function setKontakti($kontakti) {
        $this->kontakti = $kontakti;
        return $this;
    }

    public function setTvrtke($tvrtke) {
        $this->tvrtke = $tvrtke;
        return $this;
    }
	
	public function setSponzori($sponzori) {
        $this->sponzori = $sponzori;
        return $this;
    }

    public function setMediji($mediji) {
        $this->mediji = $mediji;
        return $this;
    }
    
}