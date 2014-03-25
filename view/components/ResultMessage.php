<?php

namespace view\components;
use app\view\AbstractView;

class ResultMessage extends AbstractView {
    /**
     *
     * @var string 
     */
    private $resultMessage;
    
    protected function outputHTML() {
?>
    <div>
        <?php if (null !== $this->resultMessage) {
                echo '<br><p class="alert alert-success">';
                echo $this->resultMessage;
                echo '</p>';
        }
        ?>
    </div>
<?php
    }
    
    /**
     * 
     * @param string $resultMessage
     * @return \view\components\ResultMessage
     */
    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
}