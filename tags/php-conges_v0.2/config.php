<?php


// variables � renseigner :

// $URL_ACCUEIL : url de base de php_conges (PAS termin� par un / et sans le index.php � la fin)
// $URL_ACCUEIL = "http://localhost.localdomain/php_conges";
$URL_ACCUEIL = "http://monserveurweb.mondomaine/php_conges";



// Autentification :
// Authentication :
//---------------------
// mettre a 0 si on ne veut pas d'authetification au d�marrage (est a 1 par d�faut)
// set to 0 if you don't need authentication at the begining (d�fault is 1)
$config_auth=1;


//  CONFIG  DE  LA DATABASE
//  DATABASE CONFIGURATION
//---------------------------------------
// MySql : 
$mysql_serveur="localhost" ;
$mysql_user="dbconges" ;
$mysql_pass="motdepasse";
$mysql_database= "db_conges" ;



//  DEMANDES DE CONGES
//---------------------------------------
// si � 0 : pas de saisie de demande par l'utilisateur, pas de gestion des demandes par le responsable
// si � 1 : saisie de demande par l'utilisateur, et gestion des demandes par le responsable (1 est la valeur par defaut)
// set to 0 : no query handling by user and responsable
// set to 1 : query are submited by user and handled by responsable (default is 1)
$config_user_saisie_demande = 1 ;


//  CHANGER SON PASSWORD
//---------------------------------------
// si � 0 : l'utilisateur ne peut pas changer son password
// si � 1 : l'utilisateur peut changer son password (1 est la valeur par defaut)
// set to 0 : users can change his own password
// set to 1 : users can not change his own password (default is 1)
$config_user_ch_passwd = 1 ;


//  CONFIG  DU MODE RESPONSABLE
//  RESPONSABLE MODE CONFIGURATION
//---------------------------------------
// si � 1 : dans la fenetre responsable, le menu est vertical (� gauche) (1 est la valeur par defaut)
// si � 0 : dans la fenetre responsable, le menu est horizontal (en haut)
// set to 1 : in the resp window, the menu is set verticaly (on the left) (default is 1)
// set to 0 : in the resp window, the menu is set horizontaly (at the top)
$config_resp_vertical_menu = 1 ;


//  CONFIG  DU MODE ADMINISTRATEUR
//  ADMINISTRATOR MODE CONFIGURATION
//---------------------------------------
// si � 1 : l'admin gere tous les users
// si � 0 : l'admin ne gere que les users dont il est responsable (0 est la valeur par defaut)
// set to 1 : the admin user handles all users
// set to 0 : the admin user only handles users for who he is responsable (default is 0)
$config_admin_see_all=0 ;


// Dur�e max d'inactivit� d'une session avant expiration (en secondes)
$duree_session=1800; // en secondes, 30 minutes!
//$duree_session=7200; // en secondes, 60 c'est pour la d�mo faut mettre plus (7200)!


//  CONFIG DES COULEURS
//------------------------------
// couleurs du calendrier / calendar colors
$config_semaine_bgcolor="#FFFFFF";       // couleur de fond des jours de semaine  / background color for days of the week
$config_week_end_bgcolor="#BFBFBF";      // couleur de fond des jours de week end  / background color for days of the week-end
$config_temps_partiel_bgcolor="#FFFFC4"; // couleur de fond des jours de temps partiel ou d'artt pour un user
$config_conges_bgcolor="#DEDEDE";        // couleur de fond des jours de conges (cong�s accept�s par le responsable)
$config_demande_conges_bgcolor="#E7C4C4";// couleur de fond des jours de conges demand�s (non encore accord�s par le responsable)

// Fonds de pages
$config_bgcolor="#b0c2f7";               // couleur de fond des pages
$config_bgimage="img/watback.jpg";       // image de fond des pages (PAS de / au d�but !!)

// couleurs diverses
$config_light_grey_bgcolor="#DEDEDE";


?>
