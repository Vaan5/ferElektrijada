<?php

namespace view\components;
use app\view\AbstractView;

class PersonForm extends AbstractView {
    private $postAction;
    private $submitButtonText;
    private $osoba;
	private $showPassword = true;
    private $prikazSpola = true;
    private $showDelete = true;
    private $sudjelovanje;
	private $podrucjeSudjelovanja = null;
    private $showCV = false;
    private $radnaMjesta;
    private $velicine;
    private $godine;
    private $smjerovi;
    private $zavodi;
    private $velicina;
    private $godina;
    private $smjer;
    private $radnoMjesto;
    private $zavod;
    private $showSubmit = true;
    private $showDropDown = false;
	private $controllerCV;
	private $idPodrucja = null;
	private $showTip = false;
	private $showVrstaPodrucja = false;
    
    protected function outputHTML() {
		
?>
        <form class="form-horizontal" role="form" action="<?php echo $this->postAction;?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="korisnickoime" class="col-sm-3 control-label">Korisničko ime</label>            
            <div class="col-sm-9">
            <input type="text" name="ferId" class="form-control" placeholder="Upišite korisničko ime" <?php if($this->osoba && $this->osoba->ferId){ echo 'value="' . $this->osoba->ferId . '"'; } ?> autocomplete="off"  />
            </div>  
        </div>
		
<?php if($this->showPassword) {
if($this->osoba){ ?>
	<div class="form-group">
            <label for="button" class="col-sm-3 control-label"></label>
            <div class="col-sm-9">
            <input type="button" id="promjeniLozinku" class="btn btn-default form-control" value="Promijeni lozinku" />
	    </div>
        </div>
	<div class="promjeniLozinku form-group" style="display: none;">
            <label for="staralozinka" class="col-sm-3 control-label">Stara lozinka</label>
            <div class="col-sm-9">
            <input type="password" name="password" class="form-control" placeholder="Upišite staru lozinku" autocomplete="off"  />
            </div>
        </div>
		
	<div class="promjeniLozinku form-group" style="display: none;">
            <label for="novalozinka" class="col-sm-3 control-label">Nova lozinka</label>
            <div class="col-sm-9">        
            <input type="password" name="password_new" class="form-control" placeholder="Upišite novu lozinku" autocomplete="off"  />
            </div>
        </div>
		
	<div class="promjeniLozinku form-group" style="display: none;">
            <label for="ponovilozinku" class="col-sm-3 control-label">Ponovi novu lozinku</label>
            <div class="col-sm-9">		
            <input type="password" name="password_new2" class="form-control" placeholder="Upišite povnovno lozinku" autocomplete="off"  />
            <br></div>
        </div>
	
	<span id="passBr"></span>
		
<?php } else{ ?>
		
        <div class="form-group">
            <label for="lozinka" class="col-sm-3 control-label">Lozinka</label>
             <div class="col-sm-9">
            <input type="password" name="password" class="form-control" placeholder="Upišite lozinku" />
            </div>
        </div>
		
<?php }}?>
		
		<div class="form-group">
            <label for="ime" class="col-sm-3 control-label">Ime</label>
        <div class="col-sm-9">
            <input type="text" name="ime" class="form-control" placeholder="Upišite ime" <?php if($this->osoba && $this->osoba->ime){ echo 'value="' . $this->osoba->ime . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="prezime" class="col-sm-3 control-label">Prezime</label>
        <div class="col-sm-9">
            <input type="text" name="prezime" class="form-control" placeholder="Upišite prezime" <?php if($this->osoba && $this->osoba->prezime){ echo 'value="' . $this->osoba->prezime . '"'; } ?> />
        </div>
               </div>
		<div class="form-group">
            <label for="spol" class="col-sm-3 control-label">Spol</label>
        <div class="col-sm-9">
			<input type="radio" name="spol" value="M" <?php if($this->osoba && $this->osoba->spol == 'M'){ echo 'checked'; } ?>> Muški
			&nbsp; &nbsp;
			<input type="radio" name="spol" value="Ž" <?php if($this->osoba && $this->osoba->spol == 'Ž'){ echo 'checked'; } ?>> Ženski
        </div>        
        </div>
		<div class="form-group">
            <label for="email" class="col-sm-3 control-label">E-mail</label>
        <div class="col-sm-9">
            <input type="text" name="mail" class="form-control" placeholder="Upišite e-mail" <?php if($this->osoba && $this->osoba->mail){ echo 'value="' . $this->osoba->mail . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="brojmob" class="col-sm-3 control-label">Broj mobitela</label>
        <div class="col-sm-9">        
            <input type="text" name="brojMob" class="form-control" placeholder="Upišite broj mobitela" <?php if($this->osoba && $this->osoba->brojMob){ echo 'value="' . $this->osoba->brojMob . '"'; } ?> />
        </div>
            </div>
		<div class="form-group">
            <label for="jmbag" class="col-sm-3 control-label">JMBAG</label>
        <div class="col-sm-9">        
        <input type="text" name="JMBAG" class="form-control" placeholder="Upišite JMBAG" <?php if($this->osoba && $this->osoba->JMBAG){ echo 'value="' . $this->osoba->JMBAG . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="oib" class="col-sm-3 control-label">OIB</label>
        <div class="col-sm-9">        
        <input type="text" name="OIB" class="form-control" placeholder="Upišite OIB" <?php if($this->osoba && $this->osoba->OIB){ echo 'value="' . $this->osoba->OIB . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="mbg" class="col-sm-4 control-label">Matični broj osiguranika</label>
        <div class="col-sm-8">         
        <input type="text" name="MBG" class="form-control" placeholder="Upišite matični broj" <?php if($this->osoba && $this->osoba->MBG){ echo 'value="' . $this->osoba->MBG . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="datrod" class="col-sm-4 control-label">Datum rođenja</label>
        <div class="col-sm-8">
        <input type="text" name="datRod" class="form-control datePicker" placeholder="Upišite datum rođenja" <?php if($this->osoba && $this->osoba->datRod){ echo 'value="' . $this->osoba->datRod . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="brojosobne" class="col-sm-4 control-label">Broj osobne iskaznice</label>
        <div class="col-sm-8">
        <input type="text" name="brOsobne" class="form-control" placeholder="Upišite broj osobne iskaznice" <?php if($this->osoba && $this->osoba->brOsobne){ echo 'value="' . $this->osoba->brOsobne . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="osobnavrijedido" class="col-sm-4 control-label">Osobna iskaznica vrijedi do</label>
        <div class="col-sm-8">        
        <input type="text" name="osobnaVrijediDo" class="form-control datePicker" placeholder="Upišite do kada vrijedi osobna" <?php if($this->osoba && $this->osoba->osobnaVrijediDo){ echo 'value="' . $this->osoba->osobnaVrijediDo . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="brojputovnice" class="col-sm-4 control-label">Broj putovnice</label>
        <div class="col-sm-8">        
        <input type="text" name="brPutovnice" class="form-control" placeholder="Upišite broj putovnice" <?php if($this->osoba && $this->osoba->brPutovnice){ echo 'value="' . $this->osoba->brPutovnice . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="putovnicavrijedido" class="col-sm-4 control-label">Putovnica vrijedi do</label>
        <div class="col-sm-8">          
        <input type="text" name="putovnicaVrijediDo" class="form-control datePicker" placeholder="Upišite do kada vrijedi putovnica" <?php if($this->osoba && $this->osoba->putovnicaVrijediDo){ echo 'value="' . $this->osoba->putovnicaVrijediDo . '"'; } ?> />
        </div>
                </div>
	
	<div class="form-group">
            <label for="aktivanDokument" class="col-sm-3 control-label">Dokument za putovanje</label>
        <div class="col-sm-9">
			<input type="radio" name="aktivanDokument" value="0" <?php if($this->osoba && $this->osoba->aktivanDokument == '0'){ echo 'checked'; } ?>> Putovnica
			&nbsp; &nbsp;
			<input type="radio" name="aktivanDokument" value="1" <?php if($this->osoba && $this->osoba->aktivanDokument == '1'){ echo 'checked'; } ?>> Osobna iskaznica
        </div>        
        </div>

<?php
	if ($this->showCV) {
		if($this->osoba && $this->osoba->zivotopis)
		{
?>
		
        <div class="form-group">        
        <label for="preuzmi" class="col-sm-3 control-label"></label>
            <div class="col-sm-9">
            <a href="<?php echo \route\Route::get('d3')->generate(array(
			"controller" => $this->controllerCV,
			"action" => 'downloadCV'
		));?>?id=<?php echo $this->osoba->idOsobe; ?>">Preuzmi životopis &nbsp;</a>
            <input type="checkbox" name="delete"> Obriši životopis    
            </div>
        </div>
		
		
<?php				
		}

		else
		{
?>              <div class="form-group">
		<label for="logotip" class="col-sm-3 control-label">Životopis</label>
		<div class="col-sm-9">
                <input type="file" name="datoteka" />
                </div>
                </div>
<?php
		}
	}
?>

<?php if ($this->showDropDown) { 
	 if ($this->velicine != null) {
?>
	<div class="form-group">	
                <label for="velMajice" class="col-sm-3 control-label">Veličina majice</label>
		<div class="col-sm-9">
                <select name="idVelicine" class="form-control">
			<option <?php if(!$this->velicina) echo 'selected="selected"'; ?> value=""><?php if(!$this->velicina) echo 'Odaberi...'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->velicine as $val)
		{
			echo '<option value="' . $val->idVelicine . '"';
			if ($this->velicina && $this->velicina->idVelicine == $val->idVelicine)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->velicina . '</option>';
		}
?>					
</select></div>
        </div>
	
<?php }
	if ($this->smjerovi !== null) {
?>
	
	<div class="form-group">	
                <label for="smjer" class="col-sm-3 control-label">Smjer</label>
		<div class="col-sm-9">
                <select name="idSmjera" class="form-control">
			<option <?php if(!$this->smjer) echo 'selected="selected"'; ?> value=""><?php if(!$this->smjer) echo 'Odaberi...'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->smjerovi as $val)
		{
			echo '<option value="' . $val->idSmjera . '"';
			if ($this->smjer && $this->smjer->idSmjera == $val->idSmjera)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->nazivSmjera . '</option>';
		}
?>					
</select></div>
        </div>
	
<?php }
	if ($this->zavodi !== null) {
?>
	
	<div class="form-group">	
                <label for="zavod" class="col-sm-3 control-label">Zavod</label>
		<div class="col-sm-9">
                <select name="idZavoda" class="form-control">
			<option <?php if(!$this->zavod) echo 'selected="selected"'; ?> value=""><?php if(!$this->zavod) echo 'Odaberi...'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->zavodi as $val)
		{
			echo '<option value="' . $val->idZavoda . '"';
			if ($this->zavod && $this->zavod->idZavoda == $val->idZavoda)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->skraceniNaziv . '</option>';
		}
?>					
</select></div>
        </div>
	
<?php }
	if ($this->godine !== null) {
?>
	<div class="form-group">	
                <label for="godina" class="col-sm-3 control-label">Godina studija</label>
		<div class="col-sm-9">
                <select name="idGodStud" class="form-control">
			<option <?php if(!$this->godina) echo 'selected="selected"'; ?> value=""><?php if(!$this->godina) echo 'Odaberi...'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->godine as $val)
		{
			echo '<option value="' . $val->idGodStud . '"';
			if ($this->godina && $this->godina->idGodStud == $val->idGodStud)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->godina . " " . $val->studij . '</option>';
		}
?>					
</select></div>
        </div>
	
<?php }
	if ($this->radnaMjesta !== null) {
?>	

	<div class="form-group">	
                <label for="radnomjesto" class="col-sm-3 control-label">Radno mjesto</label>
		<div class="col-sm-9">
                <select name="idRadnogMjesta" class="form-control">
			<option <?php if(!$this->radnoMjesto) echo 'selected="selected"'; ?> value=""><?php if(!$this->radnoMjesto) echo 'Odaberi...'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->radnaMjesta as $val)
		{
			echo '<option value="' . $val->idRadnogMjesta . '"';
			if ($this->radnoMjesto && $this->radnoMjesto->idRadnogMjesta == $val->idRadnogMjesta)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->naziv . '</option>';
		}
?>					
</select></div>
        </div>
	
	<?php }} 
		if ($this->showTip !== false) {
?>
		<div class="form-group">
            <label for="tip" class="col-sm-3 control-label">Tip sudionika</label>
        <div class="col-sm-9">
			<input type="radio" name="tip" value="S" <?php if ($this->sudjelovanje !== null && $this->sudjelovanje->tip == "S") echo "checked"?>> Student
			&nbsp; &nbsp;
			<input type="radio" name="tip" value="D" <?php if ($this->sudjelovanje !== null && $this->sudjelovanje->tip == "D") echo "checked"?>> Djelatnik
			<?php if (session("vrsta") === "OV") {?>
			&nbsp; &nbsp;
			<input type="radio" name="tip" value="O" <?php if ($this->sudjelovanje !== null && $this->sudjelovanje->tip == "O") echo "checked"?>> Ozsn
			<?php } ?>
        </div>        
        </div>
<?php
		}
		if ($this->showVrstaPodrucja !== false) {
?>
		<div class="form-group">
            <label for="vrstaPodrucja" class="col-sm-3 control-label">Vrsta discipline</label>
        <div class="col-sm-9">
			<input type="radio" name="vrstaPodrucja" value="1" <?php if ($this->podrucjeSudjelovanja !== null && $this->podrucjeSudjelovanja->vrstaPodrucja == 1) echo "checked" ?>> Timsko natjecanje
			&nbsp; &nbsp;
			<input type="radio" name="vrstaPodrucja" value="0" <?php if ($this->podrucjeSudjelovanja !== null && $this->podrucjeSudjelovanja->vrstaPodrucja == 0) echo "checked" ?>> Pojedinačno natjecanje
        </div>        
        </div>
<?php
		}
		if ($this->podrucjeSudjelovanja !== null) {
	?>
		
		<input type="hidden" name="idPodrucjeSudjelovanja" value="<?php echo $this->podrucjeSudjelovanja->getPrimaryKey();?>" />
	
		<?php } if($this->sudjelovanje && $this->sudjelovanje->idSudjelovanja){ ?><input type="hidden" name="idSudjelovanja" value="<?php echo $this->sudjelovanje->idSudjelovanja; ?>" /> <?php } ?>
        
		<?php if($this->osoba && $this->osoba->idOsobe){ ?><input type="hidden" name="idOsobe" value="<?php echo $this->osoba->idOsobe; ?>" /> <?php } ?>
        
		<?php if($this->idPodrucja !== null){ ?><input type="hidden" name="idPodrucja" value="<?php echo $this->idPodrucja; ?>" /> <?php } ?>
    <?php if($this->showSubmit) { ?><center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" /><?php }?>
		
		<?php if($this->osoba && $this->osoba->idOsobe && $this->showDelete === true){ ?>
		<a type="button" class="btn btn-danger deletePerson" href="<?php echo \route\Route::get('d3')->generate(array(
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
    
    public function setShowDelete($showDelete) {
        $this->showDelete = $showDelete;
        return $this;
    }
    
    public function setSudjelovanje($sudjelovanje) {
		$this->sudjelovanje = $sudjelovanje;
		return $this;
    }

    public function setShowCV($showCV) {
		$this->showCV = $showCV;
		return $this;
    }

    public function setRadnaMjesta($radnaMjesta) {
		$this->radnaMjesta = $radnaMjesta;
		return $this;
    }

    public function setVelicine($velicine) {
		$this->velicine = $velicine;
		return $this;
    }

    public function setGodine($godine) {
		$this->godine = $godine;
		return $this;
    }

    public function setSmjerovi($smjerovi) {
		$this->smjerovi = $smjerovi;
		return $this;
    }

    public function setZavodi($zavodi) {
		$this->zavodi = $zavodi;
		return $this;
    }

    public function setVelicina($velicina) {
		$this->velicina = $velicina;
		return $this;
    }

    public function setGodina($godina) {
		$this->godina = $godina;
		return $this;
    }

    public function setSmjer($smjer) {
		$this->smjer = $smjer;
		return $this;
    }

    public function setRadnoMjesto($radnoMjesto) {
		$this->radnoMjesto = $radnoMjesto;
		return $this;
    }

    public function setZavod($zavod) {
		$this->zavod = $zavod;
		return $this;
    }

    public function setShowSubmit($showSubmit) {
		$this->showSubmit = $showSubmit;
		return $this;
    }

    public function setShowDropDown($showDropDown) {
		$this->showDropDown = $showDropDown;
		return $this;
    }
	
	public function setControllerCV($controllerCV) {
		$this->controllerCV = $controllerCV;
		return $this;
	}
	
	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}

	public function setShowTip($showTip) {
		$this->showTip = $showTip;
		return $this;
	}
	
	public function setShowVrstaPodrucja($showVrstaPodrucja) {
		$this->showVrstaPodrucja = $showVrstaPodrucja;
		return $this;
	}
	
	public function setPodrucjeSudjelovanja($podrucjeSudjelovanja) {
		$this->podrucjeSudjelovanja = $podrucjeSudjelovanja;
		return $this;
	}

	public function setShowPassword($showPassword) {
		$this->showPassword = $showPassword;
		return $this;
	}

}
