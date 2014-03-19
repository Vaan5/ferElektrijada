<?php

/***********************************************************************************
 * POMOCNA BIBLIOTEKA SA NEKIM FUNKCIJCAMA KOJE BI MOGLE POSLUZITI
 * BITNE SU SLJEDECE: PREUSMJERI, __, ISLOGGEDIN, LOGIN, GET, POST, FILES
 ***********************************************************************************/


/**
 * Provjera da li postoji $haystack[$needle]
 * Vraca element ako postoji, inace $default
 * 
 * @param mixed $needle
 * @param array $haystack
 * @param mixed $default
 * @return mixed
 */
function element($needle, $haystack, $default = NULL) {
	if(isset($haystack[$needle]) && $haystack[$needle] !== '') {
		return $haystack[$needle];
	} else {
		return $default;
	}
}

/**
 * Provjerava da li postoje u polju haystack elementi s kljucevima iz needles
 * 
 * @param array $needles
 * @param array $haystack
 * @param mixed $default
 * @return array
 */
function elements($needles, $haystack, $default = NULL) {
	$novo = array();
	foreach($needles as $kljuc) {
		if(isset($haystack[$kljuc]) && $haystack[$kljuc] !== '') {	//ako ključ postoji stavi vrijednost iz $haystack
			$novo[$kljuc] = $haystack[$kljuc];
		} else {							//ako ključ ne postoji stavi default
			$novo[$kljuc] = $default;
		}
	}
	return $novo;
}

/**
 * Preusmjerava na zadanu lokaciju
 * 
 * @param string $putanja
 */
function preusmjeri($putanja) {
	header("Location: $putanja");
	die();
}

/**
 * Vraca 'cisti' string
 * 
 * @param string $string
 * @return string
 */
function __($string) {
    return htmlentities($string, ENT_QUOTES, "UTF-8");
}

/**
 * Provjerava je li korisnik logiran
 * 
 * @return number|boolean   idKorisnika | false ako nije logiran
 */
function isLoggedIn() {
    return element("auth", $_SESSION, false);
}

/**
 * Logira korisnika s zadanim id-em
 * 
 * @param string|int $id
 */
function LogIn($id) {
    $_SESSION["auth"] = $id;
}

/**
 * Vraca element iz polja $_GET s predanim $key, ako postoji,
 * inace $default
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function get($key, $default = false) {
    return element($key, $_GET, $default);
}

/**
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function post($key, $default = false) {
    return element($key, $_POST, $default);
}

/**
 * 
 * @param string $key
 * @param string $mainKey
 * @param mixed $default
 * @return mixed
 */
function files($key, $mainKey, $default = false) {
    return element($key, $_FILES[$mainKey], $default);
}