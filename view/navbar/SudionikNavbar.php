<?php
namespace view\navbar;
use app\view\AbstractView;

class SudionikNavbar extends AbstractView{
    
    protected function outputHTML() {
?>
        <ul class="nav navbar-nav">            
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'sudionik',
                    "action" => 'displayMyTeam'
                ));?>">Moj tim</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'sudionik',
                    "action" => 'displayOtherTeams'
                ));?>">Ovogodišnji timovi</a></li>
        </ul>
<?php
    }
}

