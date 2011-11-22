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
// site et numero de version de PHP_CONGES
// ne pas toucher ces variables SVP ;-)
$config_php_conges_version="0.7.1";
$config_url_site_web_php_conges="http://www.univ-montp2.fr/~ced/php_conges";




/*************************************************************************************************/
// VARIABLES A RENSEIGNER :

// IMAGE ET LIEN DE LA PAGE D'AUTENTIFICATION
$config_img_login="img/logo_um2_v.gif";                        // image en haut de la page de login de php_conges
$config_texte_img_login="retour à monserveurweb.mondomaine";    // texte de l'image
$config_lien_img_login="http://monserveurweb.mondomaine/";                                  // URL du lien de l'image de la page de login


// $URL_ACCUEIL : url de base de php_conges (PAS terminé par un / et sans le index.php à la fin)
// $URL_ACCUEIL = "http://localhost.localdomain/php_conges";
$URL_ACCUEIL_CONGES ="http://monserveurweb.mondomaine/php_conges";



// Autentification :
//---------------------
// si = FALSE : pas d'authetification au démarrage , il faut passer le parametre login à l'appel de php_conges
// si = TRUE  : la page d'autentification apparait à l'appel de php_conges TRUE est la valeur par defaut)
$config_auth = TRUE;


//  CONFIG ACCES A LA DATABASE
//---------------------------------------
// MySql : 
$mysql_serveur="localhost" ;
$mysql_user="dbconges" ;
$mysql_pass="motdepasse";
$mysql_database= "db_conges" ;



//  RESPONSABLE GENERIQUE VIRTUEL OU NON
//-------------------------------------------
// si à FALSE : le responsable qui traite les congés des personnels est une personne reelle (utilisateur de php_conges) (FALSE est la valeur par defaut)
// si à TRUE : le responsable qui traite les congés des personnels est un utilisateur generique virtuel (login=conges)
$config_responsable_virtuel = FALSE ;


//  DEMANDES DE CONGES
//---------------------------------------
// si à FALSE : pas de saisie de demande par l'utilisateur, pas de gestion des demandes par le responsable
// si à TRUE : saisie de demande par l'utilisateur, et gestion des demandes par le responsable (TRUE est la valeur par defaut)
$config_user_saisie_demande = TRUE ;



//  ENVOI DE MAIL D'INFORMATION AU RESPONSABLE
//----------------------------------------------
// si à FALSE : le responsable ne reçoit pas de mail lors d'une nouvelle demande de congés par un utilisateur (FALSE est la valeur par defaut)
// si à TRUE : le responsable reçoit un mail d'avertissement à chaque nouvelle demande de congés d'un utilisateur
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)
$config_mail_alerte_resp = FALSE ;

//  SERVEUR SMTP A UTILSER
//---------------------------------------
// adresse ip  ou  nom du serevur smpt à utiliser pour envoyer les mails
$config_serveur_smtp="smtp.mydomain" ;


//  OU TROUVER LES ADRESSES MAIL DES UTILISATEURS
//-------------------------------------------------
// plusieurs possibilité pour retrouver les adresses mail des users :
// si à "dbconges" : le mail des user se trouve dans la table users de la database db_conges
// si à "ldap"     : le mail des user se trouve dans un annuaire LDAP que l'on va intérroger (cf config ldap +bas)
// ATTENTION : toute autre valeur que "dbconges" ou "ldap" entrainera une érreur !!!
$config_where_to_find_user_email="dbconges" ;





//  GESTION DES SAMEDI ET DIMANCHES COMME TRAVAILLES OU NON
//--------------------------------------------------------------------------------------
// on définit ici si les samedis et les dimanches peuvent être travaillés ou pas.
// si à TRUE : le jour considéré est travaillé ....
// si à FALSE : le jour considéré n'est pas travaillé (weeekend).... (FALSE est la valeur par defaut)
$config_samedi_travail = FALSE ;
$config_dimanche_travail = FALSE ;


//  AFFICHAGE DU BOUTON DE CALENDRIER POUR L'UTILISATEUR
//--------------------------------------------------------------------------------------
// si à FALSE : les utilisateurs n'ont pas la possibilité d'afficher le calendrier des congés
// si à TRUE : les utilisateurs ont la possibilité d'afficher le calendrier des congés (TRUE est la valeur par defaut)
$config_user_affiche_calendrier = TRUE ;


//  AFFICHAGE DU BOUTON DE CALENDRIER POUR LE RESPONSABLE
//--------------------------------------------------------------------------------------
// si à FALSE : les responsables n'ont pas la possibilité d'afficher le calendrier des congés
// si à TRUE : les responsables ont la possibilité d'afficher le calendrier des congés (TRUE est la valeur par defaut)
$config_resp_affiche_calendrier = TRUE ;


//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC ....
//---------------------------------------------------------------
// ( les absences de ce type n'enlèvent pas de jours de congès ! )
// si à FALSE : pas de saisie par l'utilisateur des absences pour mission, formation, congrés, etc ....
// si à TRUE : saisie par l'utilisateur des absences pour mission, formation, congrés, etc .... (TRUE est la valeur par defaut)
$config_user_saisie_mission = TRUE ;


//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC .... PAR LE RESPONSABLE
//---------------------------------------------------------------------------------------
// ( les absences de ce type n'enlèvent pas de jours de congès ! )
// si à FALSE : pas de saisie par le responsable des absences pour mission, formation, congrés, etc ....(FALSE est la valeur par defaut)
// si à TRUE : saisie par le responsable des absences pour mission, formation, congrés, etc .... 
$config_resp_saisie_mission = FALSE ;


//  GESTION DES RTT COMME DES CONGES (épargne temps)
//---------------------------------------------------------------------------------------
// on gére les rtt comme des congés (demande, validation par le responsable , etc ...)
// si à FALSE : pas de gestion jours rtt comme des jours de congés
// si à TRUE : gestion jours rtt comme des jours de congés (TRUE est la valeur par defaut)
$config_rtt_comme_conges = TRUE ;


//  ECHANGE RTT OU TEMPS PARTIEL AUTORISé POUR LES UTILISATEURS
//---------------------------------------------------------------------------------------
// on autorise ou non l'utilisateur à inverser ponctuellement une jour travaillé et un jour d'absence (de rtt ou temps partiel)
// si à FALSE : pas d'échange autorisé pour l'utilisateur (FALSE est la valeur par defaut)
// si à TRUE : échange autorisé pour l'utilisateur
$config_user_echange_rtt = FALSE ;


//  CHANGER SON PASSWORD
//---------------------------------------
// si à FALSE : l'utilisateur ne peut pas changer son password
// si à TRUE : l'utilisateur peut changer son password (TRUE est la valeur par defaut)
$config_user_ch_passwd = TRUE ;


//  CONFIG  DU MODE RESPONSABLE
//---------------------------------------
// si à TRUE : dans la fenetre responsable, le menu est vertical (à gauche) (TRUE est la valeur par defaut)
// si à FALSE : dans la fenetre responsable, le menu est horizontal (en haut)
$config_resp_vertical_menu = TRUE ;


//  CONFIG  DU MODE ADMINISTRATEUR
//---------------------------------------
// si à FALSE : l'admin ne gere que les users dont il est responsable (FALSE est la valeur par defaut)
// si à TRUE : l'admin gere tous les users
$config_admin_see_all = FALSE ;


// Durée max d'inactivité d'une session avant expiration (en secondes)
$duree_session=1800; // en secondes, 30 minutes!
//$duree_session=7200; // en secondes


//  CONFIG DES STYLES
//------------------------------

$config_stylesheet_file="style_basic.css" ;
//$config_stylesheet_file="style_relook.css" ;

// Fonds de pages
$config_bgcolor="#b0c2f7";               // couleur de fond des pages
$config_bgimage="img/watback.jpg";       // image de fond des pages (PAS de / au début !!)

// couleurs diverses
$config_light_grey_bgcolor="#DEDEDE";

// NE PAS MODIFIER !!!
	// couleurs pour légende du calendrier 
	$config_semaine_bgcolor="#FFFFFF";       // couleur de fond des jours de semaine 
	$config_week_end_bgcolor="#BFBFBF";      // couleur de fond des jours de week end 
	$config_temps_partiel_bgcolor="#FFFFC4"; // couleur de fond des jours de temps partiel ou d'artt pour un user
	$config_conges_bgcolor="#DEDEDE";        // couleur de fond des jours de conges (congés acceptés par le responsable)
	$config_demande_conges_bgcolor="#E7C4C4";// couleur de fond des jours de conges demandés (non encore accordés par le responsable)
	$config_absence_autre_bgcolor="#D3FFB6"; // couleur de fond des jours d'absence pour mission, etc ...

/**************************************************************************************************************/
/* configs propres à certains environnements d'install seulement !!!...... */

// Vérification des Droits d'accés :
//---------------------
// mettre a TRUE Pour gérer les droits d'accés aux pages (est a FALSE par defaut)
$config_verif_droits = FALSE ;


/**************************************************************************************************************/
/**************************************************************************************************************/
//  ENVOI DE MAIL D'INFORMATION AU RESPONSABLE
//
// configuration du message d'alerte
// sujet du message :
$config_mail_sujet = "APPLI CONGES - Demande de congés";
// corps du message : (il y aura systématiquement le nom de la personne demandeuse au début du message)
$config_mail_contenu = " a solicité une demande de congés dans l'application.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";


?>
