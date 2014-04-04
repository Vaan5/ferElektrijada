<?php

namespace view;
use app\view\AbstractView;
use view\components\LoginForm;

class Login extends AbstractView {
    
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    protected function outputHTML() {
?>

    <?php echo new LoginForm(array(
        "actionRoute" => \route\Route::get('d3')->generate(array(
                                                            "controller" => "login",
                                                            "action" => "display"
                                                        ))
    )); ?>

    <?php echo new components\ErrorMessage(array(
        "errorMessage" => $this->errorMessage
    )); ?>

    <center><a href="<?php echo \route\Route::get('d1')->generate();?>">
		Vrati se na naslovnicu
	</a></center>
<?php
    }
    
    /**
     * 
     * @param string $errorMessage
     * @return \view\Login
     */
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }
}