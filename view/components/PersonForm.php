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
    
    private $showDelete = true;
    
    protected function outputHTML() {
		
?>
        <form class="form-horizontal" role="form" action="<?php echo $this->postAction;?>" method="POST">
        <div class="form-group">
            <label for="korisnickoime" class="col-sm-2 control-label">Korisničko ime</label>            
            <div class="col-sm-10">
            <input type="text" name="ferId" class="form-control" placeholder="Upišite korisničko ime" <?php if($this->osoba && $this->osoba->ferId){ echo 'value="' . $this->osoba->ferId . '"'; } ?> autocomplete="off"  />
            </div>  
        </div>
		
<?php if($this->osoba){ ?>
		
	<input type="button" id="promjeniLozinku" class="btn btn-default" value="Promjeni lozinku" />
		
	<div class="promjeniLozinku form-group" style="display: none;">
            <label for="staralozinka" class="col-sm-2 control-label">Stara lozinka</label>
            <div class="col-sm-10">
            <input type="password" name="password" class="form-control" placeholder="Upišite staru lozinku" autocomplete="off"  />
            </div>
        </div>
		
	<div class="promjeniLozinku form-group" style="display: none;">
            <label for="novalozinka" class="col-sm-2 control-label">Nova lozinka</label>
            <div class="col-sm-10">        
            <input type="password" name="password_new" class="form-control" placeholder="Upišite novu lozinku" autocomplete="off"  />
            </div>
        </div>
		
	<div class="promjeniLozinku form-group" style="display: none;">
            <label for="ponovilozinku" class="col-sm-2 control-label">Ponovi novu lozinku</label>
            <div class="col-sm-10">		
            <input type="password" name="password_new2" class="form-control" placeholder="Upišite povnovno lozinku" autocomplete="off"  />
	    </div>
        </div>
	
	<span id="passBr"><br><br></span>
		
<?php } else{ ?>
		
        <div class="form-group">
            <label for="lozinka" class="col-sm-2 control-label">Lozinka</label>
             <div class="col-sm-10">
            <input type="password" name="password" class="form-control" placeholder="Upišite lozinku" />
            </div>
        </div>
		
<?php }?>
		
		<div class="form-group">
            <label for="ime" class="col-sm-2 control-label">Ime</label>
        <div class="col-sm-10">
            <input type="text" name="ime" class="form-control" placeholder="Upišite ime" <?php if($this->osoba && $this->osoba->ime){ echo 'value="' . $this->osoba->ime . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="prezime" class="col-sm-2 control-label">Prezime</label>
        <div class="col-sm-10">
            <input type="text" name="prezime" class="form-control" placeholder="Upišite prezime" <?php if($this->osoba && $this->osoba->prezime){ echo 'value="' . $this->osoba->prezime . '"'; } ?> />
        </div>
               </div>
        <?php if ($this->prikazSpola) {?>
		<div class="form-group">
            <label for="spol" class="col-sm-2 control-label">Spol</label>
        <div class="col-sm-10">
            <!--
			<input type="text" name="spol" class="form-control" placeholder="M ili Ž" <?php if($this->osoba && $this->osoba->spol){ echo 'value="' . $this->osoba->spol . '"'; } ?> />
			-->
<?php if($this->osoba && $this->osoba->spol == 'Ž'){ ?>
			<input type="radio" name="spol" value="M"> Muški
			&nbsp; &nbsp;
			<input type="radio" name="spol" value="Ž" checked> Ženski
<?php } else{ ?>
			<input type="radio" name="spol" value="M" checked> Muški
			&nbsp; &nbsp;
			<input type="radio" name="spol" value="Ž"> Ženski
<?php } ?>
        </div>        
                </div>
        <?php }?>
		<div class="form-group">
            <label for="email" class="col-sm-2 control-label">E-mail</label>
        <div class="col-sm-10">
            <input type="text" name="mail" class="form-control" placeholder="Upišite e-mail" <?php if($this->osoba && $this->osoba->mail){ echo 'value="' . $this->osoba->mail . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="brojmob" class="col-sm-2 control-label">Broj mobitela</label>
        <div class="col-sm-10">        
            <input type="text" name="brojMob" class="form-control" placeholder="Upišite broj mobitela" <?php if($this->osoba && $this->osoba->brojMob){ echo 'value="' . $this->osoba->brojMob . '"'; } ?> />
        </div>
            </div>
		<div class="form-group">
            <label for="jmbag" class="col-sm-2 control-label">JMBAG</label>
        <div class="col-sm-10">        
        <input type="text" name="JMBAG" class="form-control" placeholder="Upišite JMBAG" <?php if($this->osoba && $this->osoba->JMBAG){ echo 'value="' . $this->osoba->JMBAG . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="oib" class="col-sm-2 control-label">OIB</label>
        <div class="col-sm-10">        
        <input type="text" name="OIB" class="form-control" placeholder="Upišite OIB" <?php if($this->osoba && $this->osoba->OIB){ echo 'value="' . $this->osoba->OIB . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="mbg" class="col-sm-2 control-label">Matični broj osiguranika</label>
        <div class="col-sm-10">         
        <input type="text" name="MBG" class="form-control" placeholder="Upišite matični broj" <?php if($this->osoba && $this->osoba->MBG){ echo 'value="' . $this->osoba->MBG . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="datrod" class="col-sm-2 control-label">Datum rođenja</label>
        <div class="col-sm-10">
        <input type="text" name="datRod" class="form-control datePicker" placeholder="Upišite datum rođenja" <?php if($this->osoba && $this->osoba->datRod){ echo 'value="' . $this->osoba->datRod . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="brojosobne" class="col-sm-2 control-label">Broj osobne iskaznice</label>
        <div class="col-sm-10">
        <input type="text" name="brOsobne" class="form-control" placeholder="Upišite broj osobne iskaznice" <?php if($this->osoba && $this->osoba->brOsobne){ echo 'value="' . $this->osoba->brOsobe . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="osobnavrijedido" class="col-sm-2 control-label">Osobna iskaznica vrijedi do</label>
        <div class="col-sm-10">        
        <input type="text" name="osobnaVrijediDo" class="form-control" placeholder="Upišite do kada vrijedi osobna" class="datePicker" <?php if($this->osoba && $this->osoba->osobnaVrijediDo){ echo 'value="' . $this->osoba->osobnaVrijediDo . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="brojputovnice" class="col-sm-2 control-label">Broj putovnice</label>
        <div class="col-sm-10">        
        <input type="text" name="brPutovnice" class="form-control" placeholder="Upišite broj putovnice" <?php if($this->osoba && $this->osoba->brPutovnice){ echo 'value="' . $this->osoba->brPutovnice . '"'; } ?> />
        </div>
                </div>
		<div class="form-group">
            <label for="putovnicavrijedido" class="col-sm-2 control-label">Putovnica vrijedi do</label>
        <div class="col-sm-10">          
        <input type="text" name="putovnicaVrijediDo" class="form-control" placeholder="Upišite do kada vrijedi putovnica" class="datePicker" <?php if($this->osoba && $this->osoba->putovnicaVrijediDo){ echo 'value="' . $this->osoba->putovnicaVrijediDo . '"'; } ?> />
        </div>
                </div>
        
		<?php if($this->osoba && $this->osoba->idOsobe){ ?><input type="hidden" name="idOsobe" value="<?php echo $this->osoba->idOsobe; ?>" /> <?php } ?>
        
        <center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" />
		
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

}
