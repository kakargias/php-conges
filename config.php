<?php
/*************************************************************************************************
PHP_CONGES : Gestion Interactive des Cong�s
Copyright (C) 2005 (cedric chauvineau)

Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les 
termes de la Licence Publique G�n�rale GNU publi�e par la Free Software Foundation.
Ce programme est distribu� car potentiellement utile, mais SANS AUCUNE GARANTIE, 
ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation 
dans un but sp�cifique. Reportez-vous � la Licence Publique G�n�rale GNU pour plus de d�tails.
Vous devez avoir re�u une copie de la Licence Publique G�n�rale GNU en m�me temps 
que ce programme ; si ce n'est pas le cas, �crivez � la Free Software Foundation, 
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, �tats-Unis.
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
// site et numero de version de PHP_CONGES
// ne pas toucher ces variables SVP ;-)
$config_php_conges_version="0.5";
$config_url_site_web_php_conges="http://www.univ-montp2.fr/~ced/php_conges";




/*************************************************************************************************/
// VARIABLES A RENSEIGNER :

// IMAGE ET LIEN DE LA PAGE D'AUTENTIFICATION
$config_img_login="img/logo_um2_v.gif";                        // image en haut de la page de login de php_conges
$config_texte_img_login="retour � monserveurweb.mondomaine";    // texte de l'image
$config_lien_img_login="http://monserveurweb.mondomaine/";                                  // URL du lien de l'image de la page de login


// $URL_ACCUEIL : url de base de php_conges (PAS termin� par un / et sans le index.php � la fin)
// $URL_ACCUEIL = "http://localhost.localdomain/php_conges";
$URL_ACCUEIL_CONGES ="http://monserveurweb.mondomaine/php_conges";



// Autentification :
// Authentication :
//---------------------
// mettre a 0 si on ne veut pas d'authetification au d�marrage (est a 1 par d�faut)
// si = 0 alors, il faut passer le parametre login � l'appel de php_conges
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
// si � 0 : le responsable qui traite les cong�s des personnels est une personne reelle (utilisateur de php_conges) (0 est la valeur par defaut)
// si � 1 : le responsable qui traite les cong�s des personnels est un utilisateur generique virtuel (login=conges)
$config_responsable_virtuel = 0 ;


//  DEMANDES DE CONGES
//---------------------------------------
// si � 0 : pas de saisie de demande par l'utilisateur, pas de gestion des demandes par le responsable
// si � 1 : saisie de demande par l'utilisateur, et gestion des demandes par le responsable (1 est la valeur par defaut)
$config_user_saisie_demande = 1 ;


//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC ....
//---------------------------------------------------------------
// ( les absences de ce type n'enl�vent pas de jours de cong�s ! )
// si � 0 : pas de saisie des absences pour mission, formation, congr�s, etc ....
// si � 1 : saisie des absences pour mission, formation, congr�s, etc .... (1 est la valeur par defaut)
$config_user_saisie_mission = 1 ;


//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC .... PAR LE RESPONSABLE
//---------------------------------------------------------------------------------------
// ( les absences de ce type n'enl�vent pas de jours de cong�s ! )
// si � 0 : pas de saisie par le responsable des absences pour mission, formation, congr�s, etc ....(0 est la valeur par defaut)
// si � 1 : saisie par le responsable des absences pour mission, formation, congr�s, etc .... 
$config_resp_saisie_mission = 0 ;


//  GESTION DES RTT COMME DES CONGES (�pargne temps)
//---------------------------------------------------------------------------------------
// on g�re les rtt comme des cong�s (demande, validation par le responsable , etc ...)
// si � 0 : pas de gestion jours rtt comme des jours de cong�s
// si � 1 : gestion jours rtt comme des jours de cong�s (0 est la valeur par defaut)
$config_rtt_comme_conges = 1 ;


//  ECHANGE RTT OU TEMPS PARTIEL AUTORIS� POUR LES UTILISATEURS
//---------------------------------------------------------------------------------------
// on autorise ou non l'utilisateur � inverser ponctuellement une jour travaill� et un jour d'absence (de rtt ou temps partiel)
// si � 0 : pas d'�change autoris� pour l'utilisateur (0 est la valeur par defaut)
// si � 1 : �change autoris� pour l'utilisateur
$config_user_echange_rtt = 0 ;


//  CHANGER SON PASSWORD
//---------------------------------------
// si � 0 : l'utilisateur ne peut pas changer son password
// si � 1 : l'utilisateur peut changer son password (1 est la valeur par defaut)
$config_user_ch_passwd = 1 ;


//  CONFIG  DU MODE RESPONSABLE
//  RESPONSABLE MODE CONFIGURATION
//---------------------------------------
// si � 1 : dans la fenetre responsable, le menu est vertical (� gauche) (1 est la valeur par defaut)
// si � 0 : dans la fenetre responsable, le menu est horizontal (en haut)
$config_resp_vertical_menu = 1 ;


//  CONFIG  DU MODE ADMINISTRATEUR
//  ADMINISTRATOR MODE CONFIGURATION
//---------------------------------------
// si � 1 : l'admin gere tous les users
// si � 0 : l'admin ne gere que les users dont il est responsable (0 est la valeur par defaut)
$config_admin_see_all=0 ;


// Dur�e max d'inactivit� d'une session avant expiration (en secondes)
$duree_session=1800; // en secondes, 30 minutes!
//$duree_session=7200; // en secondes, 60 c'est pour la d�mo faut mettre plus (7200)!


//  CONFIG DES COULEURS
//------------------------------

$config_stylesheet_file="style_basic.css" ;
//$config_stylesheet_file="style_relook.css" ;

// couleurs du calendrier / calendar colors
$config_semaine_bgcolor="#FFFFFF";       // couleur de fond des jours de semaine  
$config_week_end_bgcolor="#BFBFBF";      // couleur de fond des jours de week end 
$config_temps_partiel_bgcolor="#FFFFC4"; // couleur de fond des jours de temps partiel ou d'artt pour un user
$config_conges_bgcolor="#DEDEDE";        // couleur de fond des jours de conges (cong�s accept�s par le responsable)
$config_demande_conges_bgcolor="#E7C4C4";// couleur de fond des jours de conges demand�s (non encore accord�s par le responsable)
$config_absence_autre_bgcolor="#D3FFB6"; // couleur de fond des jours d'absence pour mission, etc ...

// Fonds de pages
$config_bgcolor="#b0c2f7";               // couleur de fond des pages
$config_bgimage="img/watback.jpg";       // image de fond des pages (PAS de / au d�but !!)

// couleurs diverses
$config_light_grey_bgcolor="#DEDEDE";



/**************************************************************************************************************/
/* configs propres � certains environnements d'install seulement !!!...... */

// V�rification des Droits d'acc�s :
//---------------------
// mettre a 1 Pour g�rer les droits d'acc�s aux pages (est a 0 par defaut)
$config_verif_droits = 0 ;



?>
