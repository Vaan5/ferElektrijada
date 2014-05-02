<?php
namespace view\navbar;
use app\view\AbstractView;

class SudionikNavbar extends AbstractView{
    
    protected function outputHTML() {
?>
        <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">O meni<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'sudionik',
                    "action" => 'displayMyTeam'
                ));?>">Moj tim</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'sudionik',
                    "action" => 'displayOtherTeams'
                ));?>">Ovogodišnji timovi</a></li>
          </ul>
        </li>
        </ul>
<?php
    }
}

