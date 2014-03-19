<?php

namespace ctl;
use app\controller\Controller;

class Login implements Controller {
    
    private $errorMessage;
    
    private function checkErrorMessage() {
        switch(get('neispravno')) {
            case 1:
                 $this->errorMessage = 'Morate popuniti polja!';
                break;
            case 2:
                 $this->errorMessage = 'Pogrešno uneseni podaci!';
                break;
            default:
                 $this->errorMessage = null;
                break;
        }
    }
    
    public function display() {
        // ako si vec logiran bjezi odavde
        if (\model\DBOsoba::isLoggedIn()) {
            preusmjeri(\route\Route::get('d1')->generate());
        }

        $this->checkErrorMessage();
        
        echo new \view\Main(array(
            "body" => new \view\Login(array(
                "errorMessage" => $this->errorMessage
            )),
            "title" => "Login"
        ));
    }
    
    public function login() {
        
        if(!post("userName") || !post("pass")) {
            preusmjeri(\route\Route::get('d2')->generate(array(
                "controller" => "login"
            )) . "?neispravno=1");          // vidite kako se stvaraju get zahtjevi
        }

        $validacija = new \model\LoginFormModel(array('password' => post("pass"), 'username' => post("userName")));
        $pov = $validacija->validate();
        if($pov !== true) {
            preusmjeri(\route\Route::get('d2')->generate(array(
                "controller" => "login"
            )) . "?neispravno=2");
        }
        
        $korisnik = new \model\DBOsoba();
        $korisnik->doAuth(post("userName"), post("pass"));
        
        //preusmjeri na naslovnicu
        preusmjeri(\route\Route::get('d1')->generate());
    }
    
}