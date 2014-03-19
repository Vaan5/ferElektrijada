<?php

namespace view;
use app\view\AbstractView;

class Main extends AbstractView {
    
    /**
     *
     * @var string
     */
    private $title;
    
    /**
     *
     * @var string
     */
    private $body;
    
    /**
     *
     * @var string 
     */
    private $script;
	
    /**
     * @return string html sadrzaj
     */
    protected function outputHTML() {
        ?>
<!DOCTYPE html>
<html>

    <head>
        <title><?php echo $this->title; ?></title>
        <meta charset="utf-8">
        <link href="../assets/css/bootstrap.css" rel="stylesheet">
        <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">
        <link href="../assets/css/menu.css" rel="stylesheet">

        <link href="./assets/css/bootstrap.css" rel="stylesheet">
        <link href="./assets/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="./assets/css/style.css" rel="stylesheet">
        <link href="./assets/css/menu.css" rel="stylesheet">

        <link href="assets/css/bootstrap.css" rel="stylesheet">
        <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/menu.css" rel="stylesheet">
        <?php if (null !== $this->script) {
            echo $this->script;  //<!-- Kad budete mijenjali echo $this->script; vam ispisuje skriptu recimo javascript -->
        }
        ?>
    </head>

    <body>
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
                  <span class="navbar-brand"><?php echo $this->title; ?></span>     <!-- Kad budete mijenjali echo $this->title; vam ispisuje naslov -->
                </div>

                <div class="navbar-collapse collapse">
                  <div class="navbar-right form-inline" role="form">
                        <div class="form-group"> 
                             <?php if(!\model\DBOsoba::isLoggedIn()) echo
						"<a href=\"" . \route\Route::get('d3')->generate(array(
                                                                                        "controller" => "login",
                                                                                        "action" => "display"
                                                                                        )) . "\">Prijavi se</a>"
						; else echo
						$_SESSION['user'];
					  ;?>
                        </div>
                        <div class="form-group"> 
                          Odjavi se
                        </div>
                  </div>
            </div>
          </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        
        <div class = "container-narrow">
        <?php echo $this->body; ?>      <!-- obazeno ovo mora doci tu ce se iscrtati neki drugi view s podacima npr -->
        </div>


        </body>
</html>
<?php
    }

    /**
     * 
     * @param string $title
     * 
     * @return Main
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * 
     * @param string $body
     * 
     * @return Main
     */
    public function setBody($body) {
        $this->body = $body;
        return $this;
    }
    
    /**
     * 
     * @param string $script
     * @return \templates\Main
     */
    public function setScript($script) {
        $this->script = $script;
        return $this;
    }


}