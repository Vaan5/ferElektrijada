<?php

namespace view\components;
use app\view\AbstractView;

class PersonForm extends AbstractView {
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
     * @var object
     */
    private $osoba;
    
    /**
     *
     * @var boolean 
     */
    private $prikazSpola = true;
    
    protected function outputHTML() {
		
?>
        <form role="form" action="<?php echo $this->postAction;?>" method="POST">
        <div class="form-group">
                        <label for="korisnickoime">Korisničko ime</label>
			<input type="text" name="ferId" class="form-control" placeholder="Upišite korisničko ime" <?php if($this->osoba && $this->osoba->ferId){ echo 'value="' . $this->osoba->ferId . '"'; } ?> />
        </div>
		
<?php if($this->osoba){ ?>
		
	<input type="button" id="promjeniLozinku" class="btn btn-default" value="Promjeni lozinku" />
		
	<div class="promjeniLozinku form-group" style="display: none;">
            <label for="staralozinka">Stara lozinka</label>
        <input type="password" name="password" class="form-control" placeholder="Upišite staru lozinku" />
        </div>
		
	<div class="promjeniLozinku form-group" style="display: none;">
            <label for="novalozinka">Nova lozinka</label>
        <input type="password" name="password_new" class="form-control" placeholder="Upišite novu lozinku" />
        </div>
		
	<div class="promjeniLozinku form-group" style="display: none;">
            <label for="ponovinovu">Ponovi novu lozinku</label>
        <input type="password" name="password_new2" class="form-control" placeholder="Upišite povnovno lozinku" />
        </div>
		
<?php } else{ ?>
		
        <div class="form-group">
                        <label for="lozinka">Lozinka</label>
        <input type="password" name="password" class="form-control" placeholder="Upišite lozinku" />
        </div>
		
<?php }?>
		
		<div class="form-group">
                        <label for="ime">Ime</label>
        <input type="text" name="ime" class="form-control" placeholder="Upišite ime" <?php if($this->osoba && $this->osoba->ime){ echo 'value="' . $this->osoba->ime . '"'; } ?> />
        </div>
		<div class="form-group">
                        <label for="prezime">Prezime</label>;
        <input type="text" name="prezime" class="form-control" placeholder="Upišite prezime" <?php if($this->osoba && $this->osoba->prezime){ echo 'value="' . $this->osoba->prezime . '"'; } ?> />
        </div>
        <?php if ($this->prikazSpola) {?>
		<div class="form-group">
                     <label for="spol">Spol</label>
        <input type="text" name="spol" class="form-control" placeholder="M ili Ž" <?php if($this->osoba && $this->osoba->spol){ echo 'value="' . $this->osoba->spol . '"'; } ?> />
        </div>
        <?php }?>
		<div class="form-group">
                     <label for="mail">E-mail</label>
        <input type="text" name="mail" class="form-control" placeholder="Upišite e-mail" <?php if($this->osoba && $this->osoba->mail){ echo 'value="' . $this->osoba->mail . '"'; } ?> />
        </div>
		<div class="form-group">
                     <label for="brojmob">Broj Mobitela</label>
        <input type="text" name="brojMob" class="form-control" placeholder="Upišite broj mobitela" <?php if($this->osoba && $this->osoba->brojMob){ echo 'value="' . $this->osoba->brojMob . '"'; } ?> />
        </div>
		<div class="form-group">
                     <label for="jmbag">JMBAG</label>
        <input type="text" name="JMBAG" class="form-control" placeholder="Upišite JMBAG" <?php if($this->osoba && $this->osoba->JMBAG){ echo 'value="' . $this->osoba->JMBAG . '"'; } ?> />
        </div>
		<div class="form-group">
                     <label for="oib">OIB</label>
        <input type="text" name="OIB" class="form-control" placeholder="Upišite OIB" <?php if($this->osoba && $this->osoba->OIB){ echo 'value="' . $this->osoba->OIB . '"'; } ?> />
        </div>
		<div class="form-group">
                     <label for="mbg">Matični broj osiguranika</label>
        <input type="text" name="MBG" class="form-control" placeholder="Upišite matični broj" <?php if($this->osoba && $this->osoba->MBG){ echo 'value="' . $this->osoba->MBG . '"'; } ?> />
        </div>
		<div class="form-group">
                     <label for="datrod">Datum rođenja</label>
        <input type="text" name="datRod" class="form-control" placeholder="Upišite datum rođenja" class="datePicker" <?php if($this->osoba && $this->osoba->datRod){ echo 'value="' . $this->osoba->datRod . '"'; } ?> />
        </div>
		<div class="form-group">
                     <label for="brosobne">Broj osobne iskaznice</label>
        <input type="text" name="brOsobne" class="form-control" placeholder="Upišite broj osobne iskaznice" <?php if($this->osoba && $this->osoba->brOsobne){ echo 'value="' . $this->osoba->brOsobe . '"'; } ?> />
        </div>
		<div class="form-group">
                     <label for="osobnavrijedido">Osobna iskaznica vrijedi do</label>
        <input type="text" name="osobnaVrijediDo" class="form-control" placeholder="Upišite do kada vrijedi osobna" class="datePicker" <?php if($this->osoba && $this->osoba->osobnaVrijediDo){ echo 'value="' . $this->osoba->osobnaVrijediDo . '"'; } ?> />
        </div>
		<div class="form-group">
                     <label for="brputovnice">Broj putovnice</label>
        <input type="text" name="brPutovnice" class="form-control" placeholder="Upišite broj putovnice" <?php if($this->osoba && $this->osoba->brPutovnice){ echo 'value="' . $this->osoba->brPutovnice . '"'; } ?> />
        </div>
		<div class="form-group">
                     <label for="putovnicavrijedido">Putovnica vrijedi do</label>
        <input type="text" name="putovnicaVrijediDo" class="form-control" placeholder="Upišite do kada vrijedi putovnica" class="datePicker" <?php if($this->osoba && $this->osoba->putovnicaVrijediDo){ echo 'value="' . $this->osoba->putovnicaVrijediDo . '"'; } ?> />
        </div>
        
		<?php if($this->osoba && $this->osoba->idOsobe){ ?><input type="hidden" name="idOsobe" value="<?php echo $this->osoba->idOsobe; ?>" /> <?php } ?>
        
        <center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" />
		
		<?php if($this->osoba && $this->osoba->idOsobe){ ?>
		<a type="button" class="btn btn-danger" href="<?php echo \route\Route::get('d3')->generate(array(
			"controller" => 'administrator',
			"action" => 'deleteOzsn'
		));?>?id=<?php echo $this->osoba->idOsobe; ?>">Obriši osobu</a>	
		<?php } ?></center>
        <p></p>
    </form>
    </div>
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
	
	public function setOsoba($osoba) {
        $this->osoba = $osoba;
        return $this;
    }
    
    public function setPrikazSpola($prikazSpola) {
        $this->prikazSpola = $prikazSpola;
        return $this;
    }
  
}
