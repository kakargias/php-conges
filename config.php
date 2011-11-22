<?php
/*************************************************************************************************
PHP_CONGES : Gestion Interactive des Congés
Copyright (C) 2005 (cedric chauvineau)

Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les 
termes de la Licence Publique Générale GNU publiée par la Free Software Foundation.
Ce programme est distribué car potentiellement utile, mais SANS AUCUNE GARANTIE, 
ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation 
dans un but spécifique. Reportez-vous à la Licence Publique Générale GNU pour plus de détails.
Vous devez avoir reçu une copie de la Licence Publique Générale GNU en même temps 
que ce programme ; si ce n'est pas le cas, écrivez à la Free Software Foundation, 
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, États-Unis.
*************************************************************************************************
This program is free software; you can redistribute it and/or modify it under the terms
of the GNU General Public License as published by the Free Software Foundation; either 
version 2 of the License, or any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*************************************************************************************************/



// variables à renseigner :

// $URL_ACCUEIL : url de base de php_conges (PAS terminé par un / et sans le index.php à la fin)
// $URL_ACCUEIL = "http://localhost.localdomain/php_conges";
$URL_ACCUEIL_CONGES = "http://monserveurweb.mondomaine/php_conges";



// Autentification :
// Authentication :
//---------------------
// mettre a 0 si on ne veut pas d'authetification au démarrage (est a 1 par défaut)
// set to 0 if you don't need authentication at the begining (défault is 1)
$config_auth=1;


//  CONFIG  DE  LA DATABASE
//  DATABASE CONFIGURATION
//---------------------------------------
// MySql : 
$mysql_serveur="localhost" ;
$mysql_user="dbconges" ;
$mysql_pass="motdepasse";
$mysql_database= "db_conges" ;



//  RESPONSABLE GENERIQUE VIRTUEL OU NON
//-------------------------------------------
// si à 0 : le responsable qui traite les congés des personnels est une personne reelle (utilisateur de php_conges) (0 est la valeur par defaut)
// si à 1 : le responsable qui traite les congés des personnels est un utilisateur generique virtuel (login=conges)
// set to 0 : the responsable who handles queries is a physical person (default is 0)
// set to 1 : the responasble who handles queries is a virtual person (login=conges)
$config_responsable_virtuel = 0 ;


//  DEMANDES DE CONGES
//---------------------------------------
// si à 0 : pas de saisie de demande par l'utilisateur, pas de gestion des demandes par le responsable
// si à 1 : saisie de demande par l'utilisateur, et gestion des demandes par le responsable (1 est la valeur par defaut)
// set to 0 : no query handling by user and responsable
// set to 1 : query are submited by user and handled by responsable (default is 1)
$config_user_saisie_demande = 1 ;


//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC ....
//---------------------------------------------------------------
// ( les absences de ce type n'enlèvent pas de jours de congès ! )
// si à 0 : pas de saisie des absences pour mission, formation, congrés, etc ....
// si à 1 : saisie des absences pour mission, formation, congrés, etc .... (1 est la valeur par defaut)
// set to 0 : no handling for mission vacancies
// set to 1 : user handle his own vacancies (default is 1)
$config_user_saisie_mission = 1 ;


//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC .... PAR LE RESPONSABLE
//---------------------------------------------------------------------------------------
// ( les absences de ce type n'enlèvent pas de jours de congès ! )
// si à 0 : pas de saisie par le responsable des absences pour mission, formation, congrés, etc ....(0 est la valeur par defaut)
// si à 1 : saisie par le responsable des absences pour mission, formation, congrés, etc .... 
// set to 0 : no handling by the responsable for mission vacancies (default is 0)
// set to 1 : responsable handle mission vacancies
$config_resp_saisie_mission = 0 ;


//  CHANGER SON PASSWORD
//---------------------------------------
// si à 0 : l'utilisateur ne peut pas changer son password
// si à 1 : l'utilisateur peut changer son password (1 est la valeur par defaut)
// set to 0 : users can change his own password
// set to 1 : users can not change his own password (default is 1)
$config_user_ch_passwd = 1 ;


//  CONFIG  DU MODE RESPONSABLE
//  RESPONSABLE MODE CONFIGURATION
//---------------------------------------
// si à 1 : dans la fenetre responsable, le menu est vertical (à gauche) (1 est la valeur par defaut)
// si à 0 : dans la fenetre responsable, le menu est horizontal (en haut)
// set to 1 : in the resp window, the menu is set verticaly (on the left) (default is 1)
// set to 0 : in the resp window, the menu is set horizontaly (at the top)
$config_resp_vertical_menu = 1 ;


//  CONFIG  DU MODE ADMINISTRATEUR
//  ADMINISTRATOR MODE CONFIGURATION
//---------------------------------------
// si à 1 : l'admin gere tous les users
// si à 0 : l'admin ne gere que les users dont il est responsable (0 est la valeur par defaut)
// set to 1 : the admin user handles all users
// set to 0 : the admin user only handles users for who he is responsable (default is 0)
$config_admin_see_all=0 ;


// Durée max d'inactivité d'une session avant expiration (en secondes)
$duree_session=1800; // en secondes, 30 minutes!
//$duree_session=7200; // en secondes, 60 c'est pour la démo faut mettre plus (7200)!


//  CONFIG DES COULEURS
//------------------------------
// couleurs du calendrier / calendar colors
$config_semaine_bgcolor="#FFFFFF";       // couleur de fond des jours de semaine  / background color for days of the week
$config_week_end_bgcolor="#BFBFBF";      // couleur de fond des jours de week end  / background color for days of the week-end
$config_temps_partiel_bgcolor="#FFFFC4"; // couleur de fond des jours de temps partiel ou d'artt pour un user
$config_conges_bgcolor="#DEDEDE";        // couleur de fond des jours de conges (congés acceptés par le responsable)
$config_demande_conges_bgcolor="#E7C4C4";// couleur de fond des jours de conges demandés (non encore accordés par le responsable)
$config_absence_autre_bgcolor="#D3FFB6"; // couleur de fond des jours d'absence pour mission, etc ...

// Fonds de pages
$config_bgcolor="#b0c2f7";               // couleur de fond des pages
$config_bgimage="img/watback.jpg";       // image de fond des pages (PAS de / au début !!)

// couleurs diverses
$config_light_grey_bgcolor="#DEDEDE";



/**************************************************************************************************************/
/* configs propres à certains environnements d'install seulement !!!...... */

// Vérification des Droits d'accés :
// access right control :
//---------------------
// mettre a 1 Pour gérer les droits d'accés aux pages (est a 0 par defaut)
// set to 1 to handle file access rights (default is 0)
$config_verif_droits = 0 ;



?>
