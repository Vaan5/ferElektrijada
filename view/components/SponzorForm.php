<?php

namespace view\components;
use app\view\AbstractView;

class SponzorForm extends AbstractView {
    private $route;
    private $submitButtonText;
    private $kategorije;
    private $promocije;

    protected function outputHTML() {
	// kategorije i promocije nek budu drop down list (id u value a name je kao u tablicama id)
	// dodati settere !!!!!!!!!!!!
	// od polja mi trebaju polja od sponzora + polja od imaSponzora (ono donacija i slicno), napomena nek bude textarea
	// za valute stavi drop down s onim valutama koje ante dozvoljava iz triggera
?>
    <form method="post" action="<?php echo $this->route;?>" enctype="multipart/form-data">
		<br>
		<input type="file" class="btn btn-default" name="datoteka" />
		<br>
		
		
		<input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText; ?>" />
		
		<br>
    </form>
<?php
    }
    
    
    

}