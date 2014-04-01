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
    
    protected function outputHTML() {
?>
    <form action="<?php echo $this->postAction;?>" method="POST">
        <p>Korisničko ime: &nbsp;
            <input type="text" name="ferId" placeholder="Upišite korisničko ime" />
        </p>
        <p>Lozinka: &nbsp;
        <input type="password" name="password" placeholder="Upišite lozinku" />
        </p>
		<p>Ime: &nbsp;
        <input type="text" name="ime" placeholder="Upišite ime" />
        </p>
		<p>Prezime: &nbsp;
        <input type="text" name="prezime" placeholder="Upišite prezime" />
        </p>
		<p>E-mail: &nbsp;
        <input type="text" name="mail" placeholder="Upišite e-mail" />
        </p>
		<p>Broj mobitela: &nbsp;
        <input type="text" name="brojMob" placeholder="Upišite broj mobitela" />
        </p>
		<p>JMBAG: &nbsp;
        <input type="text" name="JMBAG" placeholder="Upišite JMBAG" />
        </p>
		<p>OIB: &nbsp;
        <input type="text" name="OIB" placeholder="Upišite OIB" />
        </p>
		<p>MBG: &nbsp;
        <input type="text" name="MBG" placeholder="Upišite MBG" />
        </p>
		<p>Datum rođenja: &nbsp;
        <input type="text" name="datRod" placeholder="Upišite datum rođenja" />
        </p>
		<p>Broj osobne iskaznice: &nbsp;
        <input type="text" name="brOsobne" placeholder="Upišite broj osobne iskaznice" />
        </p>
		<p>Osobna iskaznica vrijedi do: &nbsp;
        <input type="text" name="osobnaVrijediDo" placeholder="Upišite do kada vrijedi osobna" />
        </p>
		<p>Broj putovnice: &nbsp;
        <input type="text" name="brPutovnice" placeholder="Upišite broj putovnice" />
        </p>
		<p>Putovnica vrijedi do: &nbsp;
        <input type="text" name="putovnicaVrijediDo" placeholder="Upišite do kada vrijedi putovnica" />
        </p>
        <input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" />
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
    
}
