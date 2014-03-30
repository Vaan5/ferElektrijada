<?php

namespace view\components;
use app\view\AbstractView;

class LoginForm extends AbstractView {

    protected function outputHTML() {
?>
    <form action="<?php echo \route\Route::get('d3')->generate(array(
                                                            "controller" => "login",
                                                            "action" => "display"
                                                        ));?>" method="POST">
	<br><br><br>
	<div class="login-container" style="width:350px; margin:auto">
		<div class="panel panel-default login">
			<div class="panel-body">
				<h2>Prijava</h2>
				
					<div class="form-group">
						<label for="korisničkoIme"><b>Korisničko ime</b></label>
						<input type="text" class="form-control" id="korisničkoIme" name="userName" placeholder="Upišite korisničko ime" />
					</div>
					
					<div class="form-group">
						<label for="lozinka"><b>Lozinka</b></label>
						<input type="password" class="form-control" id="šifra" name="pass" placeholder="Upišite lozinku" />
					</div>
					<br>
					
					<input type="submit" class="btn btn-primary" value="Prijavi me!" />
			</div>
		</div>
	</div>
 </form>
<?php
    } 
}