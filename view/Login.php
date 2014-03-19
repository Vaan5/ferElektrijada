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

    <?php echo new LoginForm(); ?>

    <?php echo new components\ErrorMessage(array(
        "errorMessage" => $this->errorMessage
    )); ?>

    <a href="<?php echo \route\Route::get('d1')->generate();?>">
		Vrati se na naslovnicu
	</a>
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