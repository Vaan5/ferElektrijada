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

		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
		<script src="/assets/js/bootstrap.min.js"></script>
		<link href="/assets/css/style.css" rel="stylesheet">
        <link href="/assets/css/menu.css" rel="stylesheet">
        
		<link href="./assets/css/bootstrap.min.css" rel="stylesheet">
		<script src="./assets/js/bootstrap.min.js"></script>
		<link href="./assets/css/style.css" rel="stylesheet">
        <link href="./assets/css/menu.css" rel="stylesheet">
        	
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
		<script src="../assets/js/bootstrap.min.js"></script>
		<link href="../assets/css/style.css" rel="stylesheet">
        <link href="../assets/css/menu.css" rel="stylesheet">
        <?php if (null !== $this->script) {
            echo $this->script;
        }
        ?>
    </head>

    <body>
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
        <a class="navbar-brand" href="<?php echo \route\Route::get('d1')->generate();?>">
		<span class="glyphicon glyphicon-home"></span>
	</a>
        <a class="navbar-brand">
            <!--<span class="glyphicon glyphicon-chevron-right"></span>--><?php echo " ".$this->title; ?>
        </a>
     </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">    
        <?php if(\model\DBOsoba::isLoggedIn() && ($_SESSION ['vrsta']==='O' || $_SESSION['vrsta'] === 'OV')) echo new navbar\OzsnNavbar(); ?>
        <?php if(\model\DBOsoba::isLoggedIn() && $_SESSION ['vrsta']==='A') echo new navbar\AdminNavbar(); ?>
        <?php if(\model\DBOsoba::isLoggedIn() && $_SESSION ['vrsta']==='S') echo new navbar\SudionikNavbar(); ?>
        <?php if(\model\DBOsoba::isLoggedIn() && $_SESSION ['vrsta']==='SV') echo new navbar\VoditeljNavbar(); ?>
        <ul class="nav navbar-nav navbar-right">
          <p class="navbar-text">
               <?php if(!\model\DBOsoba::isLoggedIn()) echo
						"<span class=\"glyphicon glyphicon-off\"></span> <a href=\"" . \route\Route::get('d3')->generate(array(
                                                                                        "controller" => "login",
                                                                                        "action" => "display"
                                                                                        )) . "\"> Prijava</a>"
						; elseif ($_SESSION ['vrsta']==='A') echo 
                                                    $_SESSION ['user']." (admin) ".
                                                    "<span class=\"glyphicon glyphicon-user\"></span> <a href=\"" . \route\Route::get('d3')->generate(array(
                                                                                        "controller" => "administrator",
                                                                                        "action" => "changeProfile"
                                                )) . "\"> Profil</a>"
                                                ;elseif ($_SESSION ['vrsta']==='O' || $_SESSION ['vrsta']==='OV') echo 
                                                    $_SESSION ['user']." (ozsn) ".
                                                    "<span class=\"glyphicon glyphicon-user\"></span> <a href=\"" . \route\Route::get('d3')->generate(array(
                                                                                       "controller" => 'ozsn',
                                                                                       "action" => 'displayProfile'
                                                )) . "\"> Profil</a>"
                                                ;elseif ($_SESSION ['vrsta']==='SV') echo 
                                                    $_SESSION ['user']." (voditelj) ".
                                                    "<span class=\"glyphicon glyphicon-user\"></span> <a href=\"" . \route\Route::get('d3')->generate(array(
                                                                                       "controller" => 'voditelj',
                                                                                       "action" => 'displayProfile'
                                                )) . "\"> Profil</a>"
                                                ;elseif ($_SESSION ['vrsta']==='S') echo 
                                                    $_SESSION ['user']." (sudionik) ".
                                                    "<span class=\"glyphicon glyphicon-user\"></span> <a href=\"" . \route\Route::get('d3')->generate(array(
                                                                                       "controller" => 'sudionik',
                                                                                       "action" => 'displayProfile'
                                                )) . "\"> Profil</a>"?>
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
        <?php echo $this->body; ?>
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