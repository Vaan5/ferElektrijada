<?php

namespace view\components;
use app\view\AbstractView;

class LoginForm extends AbstractView {

    private $showUserName = true;
    
    private $actionRoute;
    
    private $id;
    
    protected function outputHTML() {
?>
    <form action="<?php echo $this->actionRoute;?>" method="POST">
	<br><br><br>
	<div class="login-container" style="width:350px; margin:auto">
		<div class="panel panel-default login">
			<div class="panel-body">
				<?php if ($this->showUserName) { ?>
                            <h2>Prijava</h2>
				
					<div class="form-group">
						<label for="korisničkoIme"><b>Korisničko ime</b></label>
						<input type="text" class="form-control" id="korisničkoIme" name="userName" placeholder="Upišite korisničko ime" />
					</div>
                                <?php } ?>		
					<div class="form-group">
						<label for="lozinka"><b>Lozinka</b></label>
						<input type="password" class="form-control" id="šifra" name="pass" placeholder="Upišite lozinku" />
					</div>
					<br>
                                        
                                        <?php if ($this->id !== null) { ?>
                                            <input type="hidden" name="id" value="<?php echo $this->id;?>" />
                                        <?php }?>
					
					<input type="submit" class="btn btn-primary" value="Prijavi me!" />
			</div>
		</div>
	</div>
 </form>
<?php
    } 
    
    public function setShowUserName($showUserName) {
        $this->showUserName = $showUserName;
        return $this;
    }
    
    public function setActionRoute($actionRoute) {
        $this->actionRoute = $actionRoute;
        return $this;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }



}