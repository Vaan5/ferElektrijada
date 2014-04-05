<?php

namespace ctl;
use app\controller\Controller;

class Login implements Controller {
    
    private $errorMessage;
    
    public function display() {
        // if you're already logged in, then get out of here
        if (\model\DBOsoba::isLoggedIn()) {
            preusmjeri(\route\Route::get('d1')->generate());
        }

        // the user has filled the form
        if(!postEmpty()) {
            // if you forgot to enter something
            if(!post("userName") || !post("pass")) {
                $this->errorMessage = 'Morate popuniti sva polja!';
            } else {
                // we validate the user
                $validacija = new \model\LoginFormModel(array('password' => post("pass"), 'username' => post("userName")));
                $pov = $validacija->validate();
                
                if($pov !== true) {
                    $this->errorMessage = isset($pov['password']) ? 'Pogrešna lozinka!' : 'Pogrešno korisničko ime!';
                } else {
                    // i log you in cause everythings ok
                    $korisnik = new \model\DBOsoba();
                    $r = $korisnik->doAuth(post("userName"), post("pass"));
                    
                    $r === false ? $this->errorMessage = 'Pogrešno korisničko ime ili lozinka!' : 
                        preusmjeri(\route\Route::get('d1')->generate() . '?msg=logsuccess');
                }                
            }
        }
        
        echo new \view\Main(array(
            "body" => new \view\Login(array(
                "errorMessage" => $this->errorMessage
            )),
            "title" => "Login",
        ));
    }
    
    public function logout() {
        session_destroy();
        preusmjeri(\route\Route::get('d1')->generate());
    }
}