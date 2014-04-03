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
    <form action="<?php echo $this->postAction;?>" method="POST">
        <p>Korisničko ime: &nbsp;
			<input type="text" name="ferId" placeholder="Upišite korisničko ime" <?php if($this->osoba && $this->osoba->ferId){ echo 'value="' . $this->osoba->ferId . '"'; } ?> />
        </p>
		
<?php if($this->osoba){ ?>
		
		<input type="button" id="promjeniLozinku" class="btn btn-default" value="Promjeni lozinku" />
		
		<p class="promjeniLozinku" style="display: none;">Stara lozinka: &nbsp;
        <input type="password" name="password" placeholder="Upišite staru lozinku" />
        </p>
		
		<p class="promjeniLozinku" style="display: none;">Nova lozinka: &nbsp;
        <input type="password" name="password_new" placeholder="Upišite novu lozinku" />
        </p>
		
		<p class="promjeniLozinku" style="display: none;">Ponovi novu lozinku: &nbsp;
        <input type="password" name="password_new2" placeholder="Upišite povnovno lozinku" />
        </p>
		
<?php } else{ ?>
		
        <p>Lozinka: &nbsp;
        <input type="password" name="password" placeholder="Upišite lozinku" />
        </p>
		
<?php }?>
		
		<p>Ime: &nbsp;
        <input type="text" name="ime" placeholder="Upišite ime" <?php if($this->osoba && $this->osoba->ime){ echo 'value="' . $this->osoba->ime . '"'; } ?> />
        </p>
		<p>Prezime: &nbsp;
        <input type="text" name="prezime" placeholder="Upišite prezime" <?php if($this->osoba && $this->osoba->prezime){ echo 'value="' . $this->osoba->prezime . '"'; } ?> />
        </p>
        <?php if ($this->prikazSpola) {?>
		<p>Spol: &nbsp;
        <input type="text" name="spol" placeholder="M ili Ž" <?php if($this->osoba && $this->osoba->spol){ echo 'value="' . $this->osoba->spol . '"'; } ?> />
        </p>
        <?php }?>
		<p>E-mail: &nbsp;
        <input type="text" name="mail" placeholder="Upišite e-mail" <?php if($this->osoba && $this->osoba->mail){ echo 'value="' . $this->osoba->mail . '"'; } ?> />
        </p>
		<p>Broj mobitela: &nbsp;
        <input type="text" name="brojMob" placeholder="Upišite broj mobitela" <?php if($this->osoba && $this->osoba->brojMob){ echo 'value="' . $this->osoba->brojMob . '"'; } ?> />
        </p>
		<p>JMBAG: &nbsp;
        <input type="text" name="JMBAG" placeholder="Upišite JMBAG" <?php if($this->osoba && $this->osoba->JMBAG){ echo 'value="' . $this->osoba->JMBAG . '"'; } ?> />
        </p>
		<p>OIB: &nbsp;
        <input type="text" name="OIB" placeholder="Upišite OIB" <?php if($this->osoba && $this->osoba->OIB){ echo 'value="' . $this->osoba->OIB . '"'; } ?> />
        </p>
		<p>MBG: &nbsp;
        <input type="text" name="MBG" placeholder="Upišite MBG" <?php if($this->osoba && $this->osoba->MBG){ echo 'value="' . $this->osoba->MBG . '"'; } ?> />
        </p>
		<p>Datum rođenja: &nbsp;
        <input type="text" name="datRod" placeholder="Upišite datum rođenja" class="datePicker" <?php if($this->osoba && $this->osoba->datRod){ echo 'value="' . $this->osoba->datRod . '"'; } ?> />
        </p>
		<p>Broj osobne iskaznice: &nbsp;
        <input type="text" name="brOsobne" placeholder="Upišite broj osobne iskaznice" <?php if($this->osoba && $this->osoba->brOsobne){ echo 'value="' . $this->osoba->brOsobe . '"'; } ?> />
        </p>
		<p>Osobna iskaznica vrijedi do: &nbsp;
        <input type="text" name="osobnaVrijediDo" placeholder="Upišite do kada vrijedi osobna" class="datePicker" <?php if($this->osoba && $this->osoba->osobnaVrijediDo){ echo 'value="' . $this->osoba->osobnaVrijediDo . '"'; } ?> />
        </p>
		<p>Broj putovnice: &nbsp;
        <input type="text" name="brPutovnice" placeholder="Upišite broj putovnice" <?php if($this->osoba && $this->osoba->brPutovnice){ echo 'value="' . $this->osoba->brPutovnice . '"'; } ?> />
        </p>
		<p>Putovnica vrijedi do: &nbsp;
        <input type="text" name="putovnicaVrijediDo" placeholder="Upišite do kada vrijedi putovnica" class="datePicker" <?php if($this->osoba && $this->osoba->putovnicaVrijediDo){ echo 'value="' . $this->osoba->putovnicaVrijediDo . '"'; } ?> />
        </p>
        
		<?php if($this->osoba && $this->osoba->idOsobe){ ?><input type="hidden" name="idOsobe" value="<?php echo $this->osoba->idOsobe; ?>" /> <?php } ?>
        
        <input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" />
		
		<?php if($this->osoba && $this->osoba->idOsobe){ ?>
		<a href="<?php echo \route\Route::get('d3')->generate(array(
			"controller" => 'administrator',
			"action" => 'deleteOzsn'
		));?>?id=<?php echo $this->osoba->idOsobe; ?>">Obriši osobu</a>	
		<?php } ?>
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
	
	public function setOsoba($osoba) {
        $this->osoba = $osoba;
        return $this;
    }
    
    public function setPrikazSpola($prikazSpola) {
        $this->prikazSpola = $prikazSpola;
        return $this;
    }
  
}
