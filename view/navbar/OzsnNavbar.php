<?php
namespace view\navbar;
use app\view\AbstractView;

class OzsnNavbar extends AbstractView{
    
    protected function outputHTML() {
?>
        <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">O meni<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayUserUdruge'
                ));?>">Moje udruge</a></li>
            <li class="divider"></li>
            <li>        <a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayUserFunctions'
                ));?>">Moje funkcije</a></li>
          </ul>
        </li>
                <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tvrtke i sponzori<b class="caret"></b></a>
          <ul class="dropdown-menu">
           <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'addContact'
                ));?>">Dodavanje Kontakt Osobe</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'searchContacts'
                ));?>">Pretraživanje Kontakt Osoba</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayTvrtke'
                ));?>">Popis tvrtki</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayActiveTvrtke'
                ));?>">Korištene usluge tvrtki</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'addSponzor'
                ));?>">Dodaj sponzora</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayKategorija'
                ));?>">Kategorije sponzora</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayNacinPromocije'
                ));?>">Načini promocije</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayActiveSponzor'
                ));?>">Ovogodišnji sponzori</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayAreaSponzor'
                ));?>">Područni sponzori ovogodišnje elektrijade</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displaySponzor'
                ));?>">Sponzori</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displaySponzorsByElektrijada'
                ));?>">Sponzori po elektrijadama</a></li>


            	
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mediji<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayMediji'
                ));?>">Popis medija</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayActiveObjava'
                ));?>">Aktualne objave u medijima</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayObjava'
                ));?>">Popis objava</a></li>

          </ul>
        </li>
		
		<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Upravljanje Elektrijadom<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayTeamLeaders'
                ));?>">Voditelji</a></li>
			
			<li class="divider"></li>
			
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'searchContestants'
                ));?>">Pretraga Sudionika</a></li>
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'addContestant'
                ));?>">Dodavanje Sudionika</a></li>
			
          </ul>
        </li>
		
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tablice elemenata<b class="caret"></b></a>
          <ul class="dropdown-menu">
              <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayAtribut'
                ));?>">Atribut sudjelovanja</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayVelMajice'
                ));?>">Veličine majica</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayGodStud'
                ));?>">Godine studija</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayRadnoMjesto'
                ));?>">Radna mjesta</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayZavod'
                ));?>">Zavodi</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displaySmjer'
                ));?>">Smjerovi</a></li>
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayUsluga'
                ));?>">Usluge</a></li>
            	

          </ul>
        </li>
      </ul>
<?php
    }
}
