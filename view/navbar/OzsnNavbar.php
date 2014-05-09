<?php
namespace view\navbar;
use app\view\AbstractView;

class OzsnNavbar extends AbstractView{
    
    protected function outputHTML() {
?>
    <ul class="nav navbar-nav">     
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">O meni <b class="caret"></b></a>
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
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tvrtke i sponzori <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'searchContacts'
                ));?>">Kontakt osobe</a></li>
			
            <li class="divider"></li>
			
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayTvrtke'
                ));?>">Tvrtke</a></li>
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
                ));?>">Ovogodišnji sponzori disciplina</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displaySponzorsByElektrijada'
                ));?>">Sponzori po elektrijadama</a></li>


            	
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mediji <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayMediji'
                ));?>">Mediji</a></li>
			
            <li class="divider"></li>
			
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayActiveObjava'
                ));?>">Aktualne objave u medijima</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayObjava'
                ));?>">Objave</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayObjavaReport'
                ));?>">Pretraga objava</a></li>

          </ul>
        </li>
		
		<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Upravljanje Elektrijadom <b class="caret"></b></a>
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
                ));?>">Pretraga sudionika</a></li>
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'addContestant'
                ));?>">Dodavanje sudionika</a></li>
			
			<li class="divider"></li>
			
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayCollectedMoney'
                ));?>">Uplate sudionika</a></li>
			
			<li class="divider"></li>
			
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayCompetitionHistory'
                ));?>">Povijest sudjelovanja</a></li>
			
          </ul>
        </li>
		
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pomoćni podaci <b class="caret"></b></a>
          <ul class="dropdown-menu">
              <li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayAtribut'
                ));?>">Atribut sudionika</a></li>
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
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
                    "controller" => 'ozsn',
                    "action" => 'displayPodrucje'
                ));?>">Discipline</a></li>
            	

          </ul>
        </li>
		
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Izvještaji <b class="caret"></b></a>
          <ul class="dropdown-menu">
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
					"controller" => 'reportGenerator',
					"action" => 'generateDisciplineList'
				));?>">Popis sudionika po disciplinama</a></li>
            <li><a href="<?php echo \route\Route::get('d3')->generate(array(
					"controller" => 'reportGenerator',
					"action" => 'generateTshirtsList'
				));?>">Popis majica</a></li>
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
					"controller" => 'reportGenerator',
					"action" => 'generateYearModuleStatisticsList'
				));?>">Statistika po godinama i smjeru studiranja</a></li>
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
					"controller" => 'reportGenerator',
					"action" => 'generateYearModuleCompetitorsList'
				));?>">Popis sudionika po godini i smjeru</a></li>
			<li><a href="<?php echo \route\Route::get('d3')->generate(array(
					"controller" => 'reportGenerator',
					"action" => 'generateBusCompetitorsList'
				));?>">Popis sudionika po autobusima</a></li>
			
          </ul>
        </li>
    </ul>
<?php
    }
}
