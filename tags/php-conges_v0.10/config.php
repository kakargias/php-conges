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
$config_php_conges_version="0.10";
$config_url_site_web_php_conges="http://www.ced.univ-montp2.fr/php_conges";




/*************************************************************************************************/
// VARIABLES A RENSEIGNER :

// IMAGE ET LIEN DE LA PAGE D'AUTENTIFICATION
$config_img_login="img/logo_um2_v.gif";                        // image en haut de la page de login de php_conges
$config_texte_img_login="retour à monserveurweb.mondomaine";    // texte de l'image
$config_lien_img_login="http://monserveurweb.mondomaine/";                                  // URL du lien de l'image de la page de login

$config_titre_page_accueil="PHP_CONGES V0.10";           // Titre de la page d'accueil de php_conges
$config_titre_calendrier="CONGES : Calendrier";         // Titre de la page calendrier de php_conges
$config_titre_user_index="CONGES : Utilisateur";        // Titre des pages Utilisateur (sera suivi du login de l'utilisateur)
$config_titre_resp_index="CONGES : Page Responsable";   // Titre des pages Responsable
$config_titre_admin_index="CONGES : Administrateur";    // Titre des pages Administrateur

// $URL_ACCUEIL : url de base de php_conges (PAS terminé par un / et sans le index.php à la fin)
// $URL_ACCUEIL = "http://localhost.localdomain/php_conges";
$URL_ACCUEIL_CONGES ="http://monserveurweb.mondomaine/php_conges";



// Autentification :
//---------------------
// si = FALSE : pas d'authetification au démarrage , il faut passer le parametre login à l'appel de php_conges
// si = TRUE  : la page d'autentification apparait à l'appel de php_conges TRUE est la valeur par defaut)
$config_auth = TRUE;


// Comment vérifier le login et mot de passe des utilisateurs au démarrage :
// si à "dbconges" : l'authentification des user se fait dans la table users de la database db_conges
// si à "ldap"     : l'authentification des user se fait dans un annuaire LDAP que l'on va intérroger (cf config_ldap.php)
// si à "CAS"      : l'authentification des user se fait sur un serveur CAS que l'on va intérroger (cf config_CAS.php)
// attention : toute autre valeur que "dbconges" ou "ldap" ou "CAS" entrainera une érreur !!!
$config_how_to_connect_user="dbconges";

// Export des Users depuis LDAP :
//--------------------------------
// si = FALSE : les users sont créés "à la main" directement dans php_conges (FALSE est la valeur par defaut)
// si = TRUE  : les user sont importés du serveur LDAP (graceà une iste déroulante) (cf config_ldap.php)
$config_export_users_from_ldap=FALSE;


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



//  ENVOI DE MAILS D'INFORMATION
//----------------------------------------------
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)

// ENVOI DE MAIL AU RESPONSABLE POUR UNE NOUVELLE DEMANDE :
// si à FALSE : le responsable ne reçoit pas de mail lors d'une nouvelle demande de congés par un utilisateur (FALSE est la valeur par defaut)
// si à TRUE : le responsable reçoit un mail d'avertissement à chaque nouvelle demande de congés d'un utilisateur
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)
$config_mail_new_demande_alerte_resp = FALSE ; 

// ENVOI DE MAIL AU USER POUR UN NOUVEAU CONGES SAISI OU VALIDE :
// si à FALSE : le user ne reçoit pas de mail lorsque le responsable lui saisi ou accepte un nouveau conges (FALSE est la valeur par defaut)
// si à TRUE : le user reçoit un mail d'avertissement à chaque que le responsable saisi un nouveau congés ou accepte une demande pour lui
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)
$config_mail_valid_conges_alerte_user = FALSE ; 

// ENVOI DE MAIL AU USER POUR LE REFUS D'UNE DEMANDE DE CONGES :
// si à FALSE : le user ne reçoit pas de mail lorsque le responsable refuse une de ses demandes de conges (FALSE est la valeur par defaut)
// si à TRUE : le user reçoit un mail d'avertissement à chaque que le responsable refuse une de ses demandes de congés 
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)
$config_mail_refus_conges_alerte_user = FALSE ; 

// ENVOI DE MAIL AU USER POUR L'ANNULATION PAR LE RESP D'UN CONGES DEJA VALIDE :
// si à FALSE : le user ne reçoit pas de mail lorsque le responsable lui annule un conges (FALSE est la valeur par defaut)
// si à TRUE : le user reçoit un mail d'avertissement à chaque que le responsable annule un de ses congés
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)
$config_mail_annul_conges_alerte_user = FALSE ; 


//  SERVEUR SMTP A UTILSER
//---------------------------------------
// adresse ip  ou  nom du serveur smpt à utiliser pour envoyer les mails
$config_serveur_smtp="smtp.mydomain" ;
// Si vous ne maîtriser pas le serveur SMTP ou si, à l'utilisation, vous avez une érreur de connexion au serveur, 
//  laisser cette variable vide ("")


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




//  GESTION DES GROUPES D'UTILISATEURS
//--------------------------------------
// on définit ici si l'on veut pouvoir gèrer les utilisateurs par groupe ou pas.
// si à TRUE : les groupes d'utilisateurs sont gèrés dans l'application ....
// si à FALSE : les groupes d'utilisateurs ne sont PAS gèrés dans l'application .... (FALSE est la valeur par defaut)
$config_gestion_groupes = FALSE ;


//  EDITIONS PAPIER
//--------------------------------------
// on définit ici si le responsable peut générer des états papier des congés d'un user.
// si à TRUE : les éditions papier sont disponibles ....(TRUE est la valeur par defaut)
// si à FALSE : les éditions papier ne sont pas disponibles dans l'application .... 
$config_editions_papier = TRUE ;

//  Texte en haut des EDITIONS PAPIER
//--------------------------------------
// on définit ici le texte événtuel qui figurera en haut de page des états papier des congés d'un user.
$config_texte_haut_edition_papier = "- php_conges : édition des congés -";

//  Texte au bas des EDITIONS PAPIER
//--------------------------------------
// on définit ici le texte événtuel qui figurera en bas de page des états papier des congés d'un user.
$config_texte_bas_edition_papier = "- édité par php_conges -";




// CALENDRIER
//-------------

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


//  AFFICHAGE DU CALENDRIER : tous les utilisateurs ou les utilisateurs d'un groupe seulement
//--------------------------------------------------------------------------------------------
// si à FALSE : tous les personnes apparaissent sur le calendrier des congès (FALSE est la valeur par defaut)
// si à TRUE : seuls les personnes du même  groupe que l'utilisateur apparaissent sur le calendrier des congès
$config_affiche_groupe_in_calendrier = FALSE ;





//  SAISIE  DES CONGES ....
//--------------------------------------------------------------------------------------


//  BOUTON DE CLACUL DU NB DE JOURS PRIS
//--------------------------------------------------------------------------------------
// si à FALSE : on n'affiche pas le bouton du calcul du nb de jours pris lors de la saisie d'une nouvelle abscence
// si à TRUE : affiche le bouton du calcul du nb de jours pris lors de la saisie d'une nouvelle abscence (TRUE est la valeur par defaut)
// ATTENTION : si est à TRUE : les jours chaumés doivent être saisis (voir le module d'administration)
$config_affiche_bouton_calcul_nb_jours_pris = TRUE ;


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
//  CONFIG  DU MENU DU RESPONSABLE
//---------------------------------------
// si à TRUE : dans la fenetre responsable, le menu est vertical (à gauche) (TRUE est la valeur par defaut)
// si à FALSE : dans la fenetre responsable, le menu est horizontal (en haut)
$config_resp_vertical_menu = TRUE ;

//  CONFIG AFFICHAGE DES REFERENCES AUX EDITIONS PAPIER
//---------------------------------------------------------
// si à TRUE : indique, dans les états de congés, dans quelle édition apparait chaque congés (TRUE est la valeur par defaut)
// si à FALSE : n'affiche pas, dans les états de congés, dans quelle édition apparait chaque congés
$config_affiche_reference_edition_papier = TRUE ;


//  CONFIG  DU MODE ADMINISTRATEUR
//---------------------------------------
// si à FALSE : l'admin ne gere que les users dont il est responsable (FALSE est la valeur par defaut)
// si à TRUE : l'admin gere tous les users
$config_admin_see_all = FALSE ;


//  CHANGER LE PASSWORD D'UN UTILSATEUR
//-----------------------------------------
// si à FALSE : l'administrateur ne peut pas changer le password des utilisateurs
// si à TRUE : l'administrateur peut changer le password des utilisateurs (TRUE est la valeur par defaut)
$config_admin_change_passwd = TRUE ;



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
//  ENVOI DE MAIL D'INFORMATION 
//

/***********************/
// message d'alerte au responsable en cas de nouvelle demande de conges
// sujet du message :
$config_mail_sujet_new_demande = "APPLI CONGES - Demande de congés";
// corps du message : (il y aura systématiquement le nom de la personne demandeuse au début du message)
$config_mail_contenu_new_demande = " a solicité une demande de congés dans l'application de gestion des congès.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas de saisie de nouveau conges par le responsable
// sujet du message :
$config_mail_sujet_valid_conges = "APPLI CONGES - Congés validé";
// corps du message : (il y aura systématiquement le nom de la personne demandeuse au début du message)
$config_mail_contenu_valid_conges = " a enregistré/validé un congés pour vous dans l'application de gestion des congès.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas de refus d'une demande de conges par le responsable
// sujet du message :
$config_mail_sujet_refus_conges = "APPLI CONGES - Congés refusé";
// corps du message : (il y aura systématiquement le nom de la personne demandeuse au début du message)
$config_mail_contenu_refus_conges = " a refusé une demande de congés pour vous dans l'application de gestion des congès.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas d'annulation de conges par le responsable
// sujet du message :
$config_mail_sujet_annul_conges = "APPLI CONGES - Congés annulé";
// corps du message : (il y aura systématiquement le nom de la personne demandeuse au début du message)
$config_mail_contenu_annul_conges = " a annulé un de vos congés dans l'application de gestion des congès.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";


?>
