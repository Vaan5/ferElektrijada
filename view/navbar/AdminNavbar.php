<?php

namespace view\navbar;
use app\view\AbstractView;

class AdminNavbar extends AbstractView{
    
    protected function outputHTML() {
               ?>
        <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Elektrijade<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'displayElektrijada'
                ));?>">Elektrijade</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'addElektrijada'
                ));?>">Dodaj Elektrijadu</a></li>
          </ul>
        </li>
                <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">OZSN<b class="caret"></b></a>
          <ul class="dropdown-menu">
           <li> <a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'addOzsn'
                ));?>">Dodaj člana OZSN-a</a></li>

            <li class="divider"></li>

            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'searchOzsn'
                ));?>">Pretraži Aktivne Članove</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'listOldOzsn'
                ));?>">Prikaži Stare Članove</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'administrator',
                    "action" => 'searchPersons'
                ));?>">Pretraži Osobe</a></li>
          </ul>
        </li>
      </ul>
<?php
    }
}