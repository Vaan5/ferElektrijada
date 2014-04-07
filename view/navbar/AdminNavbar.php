<?php

namespace view\navbar;
use app\view\AbstractView;

class AdminNavbar extends AbstractView{
    
    protected function outputHTML() {
               ?>
<!DOCTYPE html>
<html>
    <body>
        <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Elektrijade<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'displayElektrijada'
                ));?>">Prikaži elektrijade</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'modifyElektrijada'
                ));?>?id=1">Uredi elektrijadu</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'addElektrijada'
                ));?>">Dodaj elektrijadu</a></li>
          </ul>
        </li>
                <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">OZSN<b class="caret"></b></a>
          <ul class="dropdown-menu">
           <li> <a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'addOzsn'
                ));?>">Dodaj člana OZSN-a</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'modifyOzsn'
                ));?>">Uredi OZSN</a></li>
            <li class="divider"></li>

            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'searchOzsn'
                ));?>">Pretraži aktivne članove</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'displayOzsn'
                ));?>?a=1">Lista aktivnih članova</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'listOldOzsn'
                ));?>">Prikaži stare OZSN</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'searchPersons'
                ));?>">Pretraži osobe</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'displayPersons'
                ));?>?a=1">Pregledaj osobe</a></li>
          </ul>
        </li>
      </ul>
    </body>
</html>
<?php
    }
}