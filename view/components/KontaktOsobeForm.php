<?php

namespace view\components;
use app\view\AbstractView;

class KontaktOsobeForm extends AbstractView {
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
	
	private $kontakt;	
	private $tvrtke;
    private $sponzori;
    private $mediji;
    private $mobiteli;
    private $mailovi;
    

    
    protected function outputHTML() {
/**
 * Za mailove i brojeve mobitela napravi s js-om da se dinamicki mogu dodavati input type =text pri cemu im kao name staljvaj sljedece
 * mail1
 * mail2
 * mail3
 * ... (koliko ih ima)
 * te
 * mob1
 * mob2
 * mob3
 * itd koliko ih ima
 */
?>
	<form class="form-horizontal" role="form" method="post" action="<?php echo $this->postAction;?>">
		<div class="form-group">
			<label for="imeKontakt" class="col-sm-3 control-label">Ime</label>
			<div class="col-sm-9">
				<input type="text" name="imeKontakt" class="form-control" placeholder="Upišite ime kontakta" <?php if($this->kontakt && $this->kontakt->imeKontakt) echo 'value="' . $this->kontakt->imeKontakt . '"' ?> />
			</div>
		</div>
		
		<div class="form-group">
			<label for="prezimeKontakt" class="col-sm-3 control-label">Prezime</label>
			<div class="col-sm-9">
				<input type="text" name="prezimeKontakt" class="form-control" placeholder="Upišite prezime kontakta" <?php if($this->kontakt && $this->kontakt->prezimeKontakt) echo 'value="' . $this->kontakt->prezimeKontakt . '"' ?> />
			</div>
		</div>
		
		<div class="form-group">
			<label for="telefon" class="col-sm-3 control-label">Broj telefona</label>
			<div class="col-sm-9">
				<input type="text" name="telefon" class="form-control" placeholder="Upišite broj telefona" <?php if($this->kontakt && $this->kontakt->telefon) echo 'value="' . $this->kontakt->telefon . '"' ?> />
			</div>
		</div>
		
		<div class="form-group">
			<label for="radnoMjesto" class="col-sm-3 control-label">Radno mjesto</label>
			<div class="col-sm-9">
				<input type="text" name="radnoMjesto" class="form-control" placeholder="Upišite radno mjesto" <?php if($this->kontakt && $this->kontakt->radnoMjesto) echo 'value="' . $this->kontakt->radnoMjesto . '"' ?> />
			</div>
		</div>
		
		<div class="form-group">
			<label for="tvrtka" class="col-sm-3 control-label">Tvrtka</label>
			<div class="col-sm-9">
				<select name="idTvrtke" class="form-control">
					<option <?php if(!$this->kontakt) echo 'selected="selected"'; ?> selected="selected" value=""><?php if(!$this->kontakt) echo 'Odaberi...'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->tvrtke as $val)
		{
			echo '<option value="' . $val->idTvrtke . '"';
			if ($this->kontakt && $this->kontakt->idTvrtke == $val->idTvrtke)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->imeTvrtke . '</option>';
		}
?>					
				</select>
			</div>
        </div>
		
		<div class="form-group">
			<label for="sponzor" class="col-sm-3 control-label">Sponzor</label>
			<div class="col-sm-9">
				<select name="idSponzora" class="form-control">
					<option <?php if(!$this->kontakt) echo 'selected="selected"'; ?> selected="selected" value=""><?php if(!$this->kontakt) echo 'Odaberi...'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->sponzori as $val)
		{
			echo '<option value="' . $val->idSponzora . '"';
			if ($this->kontakt && $this->kontakt->idSponzora == $val->idSponzora)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->imeTvrtke . '</option>';
		}
?>					
				</select>
			</div>
        </div>
		
		<div class="form-group">
			<label for="medij" class="col-sm-3 control-label">Medij</label>
			<div class="col-sm-9">
				<select name="idMedija" class="form-control">
					<option <?php if(!$this->kontakt) echo 'selected="selected"'; ?> selected="selected" value=""><?php if(!$this->kontakt) echo 'Odaberi...'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->mediji as $val)
		{
			echo '<option value="' . $val->idMedija . '"';
			if ($this->kontakt && $this->kontakt->idMedija == $val->idMedija)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->nazivMedija . '</option>';
		}
?>					
				</select>
			</div>
        </div>
<?php		
		if($this->mobiteli)
		{
			$mobNumber = count($this->mobiteli);
			$i = 0;
			
			echo '<div class="brojeviMobitela">';
			
			foreach($this->mobiteli as $val)
			{
				$i++;
?>
		
			<div class="form-group">
				<label for="mob" class="col-sm-3 control-label"><?php if($i == 1) echo 'Brojevi mobitela'; ?></label>
				<div class="col-sm-9">
					<input type="text" name="mob<?php echo $i; ?>" class="form-control<?php if($i == $mobNumber) echo ' lastMob" data-number="' . $i; ?>" value="<?php echo $val->broj; ?>" />
				</div>
			</div>
<?php
			}
			
			echo '</div>';
		}
		
		else
		{			
?>
		<div class="brojeviMobitela">
			<div class="form-group">
				<label for="mob" class="col-sm-3 control-label">Brojevi mobitela</label>
				<div class="col-sm-9">
					<input type="text" name="mob1" class="form-control lastMob" data-number="1" placeholder="Upišite broj mobitela" />
				</div>
			</div>
		</div>
		
<?php } ?>
		
		<div class="form-group">
			<label for="mob" class="col-sm-3 control-label"></label>
			<div class="col-sm-9">
				<a id="dodajMobPolje" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj još jedno polje</a>
			</div>
		</div>
			
		<?php		
		if($this->mailovi)
		{
			$mailNumber = count($this->mailovi);
			$j = 0;
			
			echo '<div class="mailovi">';
			
			foreach($this->mailovi as $val)
			{
				$j++;
?>
		
			<div class="form-group">
				<label for="mail" class="col-sm-3 control-label"><?php if($j == 1) echo 'E-mail adrese'; ?></label>
				<div class="col-sm-9">
					<input type="text" name="mail<?php echo $j; ?>" class="form-control<?php if($j == $mailNumber) echo ' lastMail" data-number="' . $j; ?>" value="<?php echo $val->email; ?>" />
				</div>
			</div>
<?php
			}
			
			echo '</div>';
		}
		
		else
		{			
?>
		<div class="mailovi">
			<div class="form-group">
				<label for="mail" class="col-sm-3 control-label">E-mail adrese</label>
				<div class="col-sm-9">
					<input type="text" name="mail1" class="form-control lastMail" data-number="1" placeholder="Upišite e-mail adresu" />
				</div>
			</div>
		</div>
		
<?php } ?>
		
		<div class="form-group">
			<label for="moail" class="col-sm-3 control-label"></label>
			<div class="col-sm-9">
				<a id="dodajMailPolje" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj još jedno polje</a>
			</div>
		</div>
			
		<input type="hidden" name="id" value="<?php echo $this->kontakt->idKontakta?>"/>
		<center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText; ?>" /></center>
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
	
	public function setKontakt($kontakt) {
        $this->kontakt = $kontakt;
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
    
    public function setMobiteli($mobiteli) {
	$this->mobiteli = $mobiteli;
	return $this;
    }

    public function setMailovi($mailovi) {
	$this->mailovi = $mailovi;
	return $this;
    }
    
}
