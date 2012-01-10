<?php

	$langage = 'fr_FR';
	
	if (!empty($_REQUEST['lang'])) { // Si l'utilisateur a choisi une langue
		switch ($_REQUEST['lang']) { // En fonction de la langue, on crée une variable $langage qui contient le code
			case 'en':
				$langage = 'en_US';
				break;
			case 'es':
				$langage = 'es_ES';
				break;
			case 'fr':
			default:
				$langage = 'fr_FR';
				break;
		}
	}
	else
	{	
		$data = array_map("trim", explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]));
		$data = array_map("trim", explode(";", $data[0]));
		$data = array_map("trim", explode("-", $data[0])); //get first pair of language-country 
		 
		$language = strtoupper($data[0]); 
		$country  = strtoupper($data[1]); 
		
		$php_locale = strtolower($language) . ( empty($country) ?  '' : '_'.$country );
		if ( !in_array($php_locale , array('en_US', 'fr_FR', 'es_ES' )))
		{
			$php_locale = 'fr_FR';
		}
	}
	
	
	putenv('LANG='.$langage); // On modifie la variable d'environnement
	setlocale(LC_ALL, $langage); // On modifie les informations de localisation en fonction de la langue
	
	$nomDesFichiersDeLangue = 'php-conges'; // Le nom de nos fichiers .mo
	
	bindtextdomain($nomDesFichiersDeLangue, LOCALE_PATH ); // On indique le chemin vers les fichiers .mo
	textdomain($nomDesFichiersDeLangue); // Le nom du domaine par défaut
