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
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<link href="../assets/css/bootstrap.css" rel="stylesheet">
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="../assets/css/bootstrap-theme.css" rel="stylesheet">
		<link href="../assets/css/bootstrap-theme.min.css" rel="stylesheet">
		<script src="../assets/js/bootstrap.min.js"></script>
		<link href="../assets/css/style.css" rel="stylesheet">
        <link href="../assets/css/menu.css" rel="stylesheet">

		<link href="./assets/css/bootstrap.css" rel="stylesheet">
		<link href="./assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="./assets/css/bootstrap-theme.css" rel="stylesheet">
		<link href="./assets/css/bootstrap-theme.min.css" rel="stylesheet">
		<script src="./assets/js/bootstrap.min.js"></script>
		<link href="./assets/css/style.css" rel="stylesheet">
        <link href="./assets/css/menu.css" rel="stylesheet">

        <link href="/assets/css/bootstrap.css" rel="stylesheet">
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="/assets/css/bootstrap-theme.css" rel="stylesheet">
		<link href="/assets/css/bootstrap-theme.min.css" rel="stylesheet">
		<script src="/assets/js/bootstrap.min.js"></script>
		<link href="/assets/css/style.css" rel="stylesheet">
        <link href="/assets/css/menu.css" rel="stylesheet">
        <?php if (null !== $this->script) {
            echo $this->script;  //<!-- Kad budete mijenjali echo $this->script; vam ispisuje skriptu recimo javascript -->
        }
        ?>
    </head>

    <body>
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <a class="navbar-brand" href="<?php echo \route\Route::get('d1')->generate();?>">
		<span class="glyphicon glyphicon-home"></span> Naslovnica
	</a>
        <p class="navbar-brand">
            <span class="glyphicon glyphicon-chevron-right"></span><?php echo " ".$this->title; ?>
        </p>
     </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">		
      <ul class="nav navbar-nav navbar-right">
          <p class="navbar-text">
               <?php if(!\model\DBOsoba::isLoggedIn()) echo
						"<span class=\"glyphicon glyphicon-off\"></span> <a href=\"" . \route\Route::get('d3')->generate(array(
                                                                                        "controller" => "login",
                                                                                        "action" => "display"
                                                                                        )) . "\"> Prijava</a>"
						; elseif ($_SESSION ['vrsta']==='A') echo 
                                                    $_SESSION ['user']." ".
                                                    "<span class=\"glyphicon glyphicon-user\"></span> <a href=\"" . \route\Route::get('d3')->generate(array(
                                                                                        "controller" => "administrator",
                                                                                        "action" => "changeProfile"
                                                )) . "\"> Profil</a>"
				 ;?>
				<?php if(\model\DBOsoba::isLoggedIn())     echo "<span class=\"glyphicon glyphicon-off\"></span><a href=\"" . \route\Route::get('d3')->generate(array(
                                                                                        "controller" => "login",
                                                                                        "action" => "logout"
                                                                                        )) . "\"> Odjava</a>";
                 ?>
          </p>
          </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
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