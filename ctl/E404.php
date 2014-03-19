<?php

namespace ctl;

use app\controller\Controller;

class E404 implements Controller {

    public function display() {

        header("HTTP/1.1 404 Not found");

        echo new \view\Main(array(
            "title" => "Nije pronađena stranica",
            "body" => new \view\E404()
        ));
    }
}