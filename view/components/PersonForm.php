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
