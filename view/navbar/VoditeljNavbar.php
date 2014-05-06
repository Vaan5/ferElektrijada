<?php
namespace view\navbar;
use app\view\AbstractView;

class VoditeljNavbar extends AbstractView{
    
    protected function outputHTML() {
?>
        <ul class="nav navbar-nav">
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'voditelj',
                    "action" => 'displayPodrucja'
                ));?>">Discipline</a></li>
        </ul>
<?php
    }
}
