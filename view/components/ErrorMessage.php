<?php

namespace view\components;
use app\view\AbstractView;

class ErrorMessage extends AbstractView {
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    protected function outputHTML() {
?>
    <div>
        <?php if (null !== $this->errorMessage) {
                echo '<br><p class="alert alert-danger">';
                echo $this->errorMessage;
                echo '</p>';
        }
        ?>
    </div>
<?php
    }
    
    /**
     * 
     * @param string $errorMessage
     * @return view\components\ErrorMessage
     */
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }
}