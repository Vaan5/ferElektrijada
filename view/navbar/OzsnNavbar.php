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
                ));?>">Moje Udruge</a></li>
            <li class="divider"></li>
            <li>        <a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayUserFunctions'
                ));?>">Moje Funkcije</a></li>
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
                    "action" => 'displaySponzor'
                ));?>">Sponzori</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayKategorija'
                ));?>">Kategorije Sponzora</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayNacinPromocije'
                ));?>">Načini Promocije</a></li>
			
            <li class="divider"></li>
			
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayActiveSponzor'
                ));?>">Ovogodišnji Sponzori</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayAreaSponzor'
                ));?>">Područni sponzori ovogodišnje elektrijade</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displaySponzorsByElektrijada'
                ));?>">Sponzori po Elektrijadama</a></li>


            	
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
                    "action" => 'modifyElektrijada'
                ));?>">Elektrijada</a></li>
			
			<li class="divider"></li>
			
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
			
			<li class="divider"></li>
			
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayCollectedMoney'
                ));?>">Uplate Sudionika</a></li>
			
          </ul>
        </li>
		
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pomoćni podaci<b class="caret"></b></a>
          <ul class="dropdown-menu">
              <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayAtribut'
                ));?>">Atribut Sudionika</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayVelMajice'
                ));?>">Veličine Majica</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayGodStud'
                ));?>">Godine Studija</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayRadnoMjesto'
                ));?>">Radna Mjesta</a></li>
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
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayMediji'
                ));?>">Mediji</a></li>
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayFunkcija'
                ));?>">Funkcije</a></li>
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayUdruga'
                ));?>">Udruge</a></li>
            	

          </ul>
        </li>
      </ul>
<?php
    }
}
