<?php

namespace view\administrator;
use app\view\AbstractView;
use view\components\LoginForm;

class AdminDoubleCheck extends AbstractView {
    
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    private $id;
    
    protected function outputHTML() {
?>

    <?php echo new LoginForm(array(
        "showUserName" => false,
        "actionRoute" => \route\Route::get('d3')->generate(array(
            "controller" => "administrator",
            "action" => "doubleCheckAdmin"
        )),
        "id" => $this->id
    )); ?>

    <?php echo new \view\components\ErrorMessage(array(
        "errorMessage" => $this->errorMessage
    )); ?>

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
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }


}