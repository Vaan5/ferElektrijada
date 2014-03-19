<?php

namespace ctl;
use app\controller\Controller;

class Index implements Controller {
    
    public function display() {
        echo new \view\Main(array(
            "body" => new \view\Index(array(
                "varijablaPrenesenaIzControllera" => "Varijabla koju sam mogao staviti da sam htio! => izmijeniti ctl\Index!"
            )),
            "title" => "Welcome to FER!"
        ));
    }
}