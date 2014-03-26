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
		<div class="form-group">
            <label for="korisničkoIme"><b>Korisničko ime</b></label>
            <input type="text" class="form-control" id="korisničkoIme" name="userName" placeholder="Upišite korisničko ime" />
		</div>
		<br>
		<div class="form-group">
            <label for="šifra"><b>Šifra</b></label>
            <input type="password" class="form-control" id="šifra" name="pass" placeholder="Upišite šifru" />
		</div>
		<br>
		<input type="submit" class="btn btn-primary" value="Prijavi me!" />
		<br><br><br>
    </form>
    
<?php
    } 
}