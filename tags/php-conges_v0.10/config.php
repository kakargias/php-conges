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
$config_php_conges_version="0.10";
$config_url_site_web_php_conges="http://www.ced.univ-montp2.fr/php_conges";




/*************************************************************************************************/
// VARIABLES A RENSEIGNER :

// IMAGE ET LIEN DE LA PAGE D'AUTENTIFICATION
$config_img_login="img/logo_um2_v.gif";                        // image en haut de la page de login de php_conges
$config_texte_img_login="retour � monserveurweb.mondomaine";    // texte de l'image
$config_lien_img_login="http://monserveurweb.mondomaine/";                                  // URL du lien de l'image de la page de login

$config_titre_page_accueil="PHP_CONGES V0.10";           // Titre de la page d'accueil de php_conges
$config_titre_calendrier="CONGES : Calendrier";         // Titre de la page calendrier de php_conges
$config_titre_user_index="CONGES : Utilisateur";        // Titre des pages Utilisateur (sera suivi du login de l'utilisateur)
$config_titre_resp_index="CONGES : Page Responsable";   // Titre des pages Responsable
$config_titre_admin_index="CONGES : Administrateur";    // Titre des pages Administrateur

// $URL_ACCUEIL : url de base de php_conges (PAS termin� par un / et sans le index.php � la fin)
// $URL_ACCUEIL = "http://localhost.localdomain/php_conges";
$URL_ACCUEIL_CONGES ="http://monserveurweb.mondomaine/php_conges";



// Autentification :
//---------------------
// si = FALSE : pas d'authetification au d�marrage , il faut passer le parametre login � l'appel de php_conges
// si = TRUE  : la page d'autentification apparait � l'appel de php_conges TRUE est la valeur par defaut)
$config_auth = TRUE;


// Comment v�rifier le login et mot de passe des utilisateurs au d�marrage :
// si � "dbconges" : l'authentification des user se fait dans la table users de la database db_conges
// si � "ldap"     : l'authentification des user se fait dans un annuaire LDAP que l'on va int�rroger (cf config_ldap.php)
// si � "CAS"      : l'authentification des user se fait sur un serveur CAS que l'on va int�rroger (cf config_CAS.php)
// attention : toute autre valeur que "dbconges" ou "ldap" ou "CAS" entrainera une �rreur !!!
$config_how_to_connect_user="dbconges";

// Export des Users depuis LDAP :
//--------------------------------
// si = FALSE : les users sont cr��s "� la main" directement dans php_conges (FALSE est la valeur par defaut)
// si = TRUE  : les user sont import�s du serveur LDAP (grace� une iste d�roulante) (cf config_ldap.php)
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
// si � FALSE : le responsable qui traite les cong�s des personnels est une personne reelle (utilisateur de php_conges) (FALSE est la valeur par defaut)
// si � TRUE : le responsable qui traite les cong�s des personnels est un utilisateur generique virtuel (login=conges)
$config_responsable_virtuel = FALSE ;


//  DEMANDES DE CONGES
//---------------------------------------
// si � FALSE : pas de saisie de demande par l'utilisateur, pas de gestion des demandes par le responsable
// si � TRUE : saisie de demande par l'utilisateur, et gestion des demandes par le responsable (TRUE est la valeur par defaut)
$config_user_saisie_demande = TRUE ;



//  ENVOI DE MAILS D'INFORMATION
//----------------------------------------------
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)

// ENVOI DE MAIL AU RESPONSABLE POUR UNE NOUVELLE DEMANDE :
// si � FALSE : le responsable ne re�oit pas de mail lors d'une nouvelle demande de cong�s par un utilisateur (FALSE est la valeur par defaut)
// si � TRUE : le responsable re�oit un mail d'avertissement � chaque nouvelle demande de cong�s d'un utilisateur
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)
$config_mail_new_demande_alerte_resp = FALSE ; 

// ENVOI DE MAIL AU USER POUR UN NOUVEAU CONGES SAISI OU VALIDE :
// si � FALSE : le user ne re�oit pas de mail lorsque le responsable lui saisi ou accepte un nouveau conges (FALSE est la valeur par defaut)
// si � TRUE : le user re�oit un mail d'avertissement � chaque que le responsable saisi un nouveau cong�s ou accepte une demande pour lui
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)
$config_mail_valid_conges_alerte_user = FALSE ; 

// ENVOI DE MAIL AU USER POUR LE REFUS D'UNE DEMANDE DE CONGES :
// si � FALSE : le user ne re�oit pas de mail lorsque le responsable refuse une de ses demandes de conges (FALSE est la valeur par defaut)
// si � TRUE : le user re�oit un mail d'avertissement � chaque que le responsable refuse une de ses demandes de cong�s 
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)
$config_mail_refus_conges_alerte_user = FALSE ; 

// ENVOI DE MAIL AU USER POUR L'ANNULATION PAR LE RESP D'UN CONGES DEJA VALIDE :
// si � FALSE : le user ne re�oit pas de mail lorsque le responsable lui annule un conges (FALSE est la valeur par defaut)
// si � TRUE : le user re�oit un mail d'avertissement � chaque que le responsable annule un de ses cong�s
// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)
$config_mail_annul_conges_alerte_user = FALSE ; 


//  SERVEUR SMTP A UTILSER
//---------------------------------------
// adresse ip  ou  nom du serveur smpt � utiliser pour envoyer les mails
$config_serveur_smtp="smtp.mydomain" ;
// Si vous ne ma�triser pas le serveur SMTP ou si, � l'utilisation, vous avez une �rreur de connexion au serveur, 
//  laisser cette variable vide ("")


//  OU TROUVER LES ADRESSES MAIL DES UTILISATEURS
//-------------------------------------------------
// plusieurs possibilit� pour retrouver les adresses mail des users :
// si � "dbconges" : le mail des user se trouve dans la table users de la database db_conges
// si � "ldap"     : le mail des user se trouve dans un annuaire LDAP que l'on va int�rroger (cf config ldap +bas)
// ATTENTION : toute autre valeur que "dbconges" ou "ldap" entrainera une �rreur !!!
$config_where_to_find_user_email="dbconges" ;




//  GESTION DES SAMEDI ET DIMANCHES COMME TRAVAILLES OU NON
//--------------------------------------------------------------------------------------
// on d�finit ici si les samedis et les dimanches peuvent �tre travaill�s ou pas.
// si � TRUE : le jour consid�r� est travaill� ....
// si � FALSE : le jour consid�r� n'est pas travaill� (weeekend).... (FALSE est la valeur par defaut)
$config_samedi_travail = FALSE ;
$config_dimanche_travail = FALSE ;




//  GESTION DES GROUPES D'UTILISATEURS
//--------------------------------------
// on d�finit ici si l'on veut pouvoir g�rer les utilisateurs par groupe ou pas.
// si � TRUE : les groupes d'utilisateurs sont g�r�s dans l'application ....
// si � FALSE : les groupes d'utilisateurs ne sont PAS g�r�s dans l'application .... (FALSE est la valeur par defaut)
$config_gestion_groupes = FALSE ;


//  EDITIONS PAPIER
//--------------------------------------
// on d�finit ici si le responsable peut g�n�rer des �tats papier des cong�s d'un user.
// si � TRUE : les �ditions papier sont disponibles ....(TRUE est la valeur par defaut)
// si � FALSE : les �ditions papier ne sont pas disponibles dans l'application .... 
$config_editions_papier = TRUE ;

//  Texte en haut des EDITIONS PAPIER
//--------------------------------------
// on d�finit ici le texte �v�ntuel qui figurera en haut de page des �tats papier des cong�s d'un user.
$config_texte_haut_edition_papier = "- php_conges : �dition des cong�s -";

//  Texte au bas des EDITIONS PAPIER
//--------------------------------------
// on d�finit ici le texte �v�ntuel qui figurera en bas de page des �tats papier des cong�s d'un user.
$config_texte_bas_edition_papier = "- �dit� par php_conges -";




// CALENDRIER
//-------------

//  AFFICHAGE DU BOUTON DE CALENDRIER POUR L'UTILISATEUR
//--------------------------------------------------------------------------------------
// si � FALSE : les utilisateurs n'ont pas la possibilit� d'afficher le calendrier des cong�s
// si � TRUE : les utilisateurs ont la possibilit� d'afficher le calendrier des cong�s (TRUE est la valeur par defaut)
$config_user_affiche_calendrier = TRUE ;


//  AFFICHAGE DU BOUTON DE CALENDRIER POUR LE RESPONSABLE
//--------------------------------------------------------------------------------------
// si � FALSE : les responsables n'ont pas la possibilit� d'afficher le calendrier des cong�s
// si � TRUE : les responsables ont la possibilit� d'afficher le calendrier des cong�s (TRUE est la valeur par defaut)
$config_resp_affiche_calendrier = TRUE ;


//  AFFICHAGE DU CALENDRIER : tous les utilisateurs ou les utilisateurs d'un groupe seulement
//--------------------------------------------------------------------------------------------
// si � FALSE : tous les personnes apparaissent sur le calendrier des cong�s (FALSE est la valeur par defaut)
// si � TRUE : seuls les personnes du m�me  groupe que l'utilisateur apparaissent sur le calendrier des cong�s
$config_affiche_groupe_in_calendrier = FALSE ;





//  SAISIE  DES CONGES ....
//--------------------------------------------------------------------------------------


//  BOUTON DE CLACUL DU NB DE JOURS PRIS
//--------------------------------------------------------------------------------------
// si � FALSE : on n'affiche pas le bouton du calcul du nb de jours pris lors de la saisie d'une nouvelle abscence
// si � TRUE : affiche le bouton du calcul du nb de jours pris lors de la saisie d'une nouvelle abscence (TRUE est la valeur par defaut)
// ATTENTION : si est � TRUE : les jours chaum�s doivent �tre saisis (voir le module d'administration)
$config_affiche_bouton_calcul_nb_jours_pris = TRUE ;


//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC ....
//---------------------------------------------------------------
// ( les absences de ce type n'enl�vent pas de jours de cong�s ! )
// si � FALSE : pas de saisie par l'utilisateur des absences pour mission, formation, congr�s, etc ....
// si � TRUE : saisie par l'utilisateur des absences pour mission, formation, congr�s, etc .... (TRUE est la valeur par defaut)
$config_user_saisie_mission = TRUE ;


//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC .... PAR LE RESPONSABLE
//---------------------------------------------------------------------------------------
// ( les absences de ce type n'enl�vent pas de jours de cong�s ! )
// si � FALSE : pas de saisie par le responsable des absences pour mission, formation, congr�s, etc ....(FALSE est la valeur par defaut)
// si � TRUE : saisie par le responsable des absences pour mission, formation, congr�s, etc .... 
$config_resp_saisie_mission = FALSE ;


//  GESTION DES RTT COMME DES CONGES (�pargne temps)
//---------------------------------------------------------------------------------------
// on g�re les rtt comme des cong�s (demande, validation par le responsable , etc ...)
// si � FALSE : pas de gestion jours rtt comme des jours de cong�s
// si � TRUE : gestion jours rtt comme des jours de cong�s (TRUE est la valeur par defaut)
$config_rtt_comme_conges = TRUE ;


//  ECHANGE RTT OU TEMPS PARTIEL AUTORIS� POUR LES UTILISATEURS
//---------------------------------------------------------------------------------------
// on autorise ou non l'utilisateur � inverser ponctuellement une jour travaill� et un jour d'absence (de rtt ou temps partiel)
// si � FALSE : pas d'�change autoris� pour l'utilisateur (FALSE est la valeur par defaut)
// si � TRUE : �change autoris� pour l'utilisateur
$config_user_echange_rtt = FALSE ;


//  CHANGER SON PASSWORD
//---------------------------------------
// si � FALSE : l'utilisateur ne peut pas changer son password
// si � TRUE : l'utilisateur peut changer son password (TRUE est la valeur par defaut)
$config_user_ch_passwd = TRUE ;




//  CONFIG  DU MODE RESPONSABLE
//---------------------------------------
//  CONFIG  DU MENU DU RESPONSABLE
//---------------------------------------
// si � TRUE : dans la fenetre responsable, le menu est vertical (� gauche) (TRUE est la valeur par defaut)
// si � FALSE : dans la fenetre responsable, le menu est horizontal (en haut)
$config_resp_vertical_menu = TRUE ;

//  CONFIG AFFICHAGE DES REFERENCES AUX EDITIONS PAPIER
//---------------------------------------------------------
// si � TRUE : indique, dans les �tats de cong�s, dans quelle �dition apparait chaque cong�s (TRUE est la valeur par defaut)
// si � FALSE : n'affiche pas, dans les �tats de cong�s, dans quelle �dition apparait chaque cong�s
$config_affiche_reference_edition_papier = TRUE ;


//  CONFIG  DU MODE ADMINISTRATEUR
//---------------------------------------
// si � FALSE : l'admin ne gere que les users dont il est responsable (FALSE est la valeur par defaut)
// si � TRUE : l'admin gere tous les users
$config_admin_see_all = FALSE ;


//  CHANGER LE PASSWORD D'UN UTILSATEUR
//-----------------------------------------
// si � FALSE : l'administrateur ne peut pas changer le password des utilisateurs
// si � TRUE : l'administrateur peut changer le password des utilisateurs (TRUE est la valeur par defaut)
$config_admin_change_passwd = TRUE ;



// Dur�e max d'inactivit� d'une session avant expiration (en secondes)
$duree_session=1800; // en secondes, 30 minutes!
//$duree_session=7200; // en secondes


//  CONFIG DES STYLES
//------------------------------

$config_stylesheet_file="style_basic.css" ;
//$config_stylesheet_file="style_relook.css" ;

// Fonds de pages
$config_bgcolor="#b0c2f7";               // couleur de fond des pages
$config_bgimage="img/watback.jpg";       // image de fond des pages (PAS de / au d�but !!)

// couleurs diverses
$config_light_grey_bgcolor="#DEDEDE";

// NE PAS MODIFIER !!!
	// couleurs pour l�gende du calendrier 
	$config_semaine_bgcolor="#FFFFFF";       // couleur de fond des jours de semaine 
	$config_week_end_bgcolor="#BFBFBF";      // couleur de fond des jours de week end 
	$config_temps_partiel_bgcolor="#FFFFC4"; // couleur de fond des jours de temps partiel ou d'artt pour un user
	$config_conges_bgcolor="#DEDEDE";        // couleur de fond des jours de conges (cong�s accept�s par le responsable)
	$config_demande_conges_bgcolor="#E7C4C4";// couleur de fond des jours de conges demand�s (non encore accord�s par le responsable)
	$config_absence_autre_bgcolor="#D3FFB6"; // couleur de fond des jours d'absence pour mission, etc ...

/**************************************************************************************************************/
/* configs propres � certains environnements d'install seulement !!!...... */

// V�rification des Droits d'acc�s :
//---------------------
// mettre a TRUE Pour g�rer les droits d'acc�s aux pages (est a FALSE par defaut)
$config_verif_droits = FALSE ;


/**************************************************************************************************************/
/**************************************************************************************************************/
//  ENVOI DE MAIL D'INFORMATION 
//

/***********************/
// message d'alerte au responsable en cas de nouvelle demande de conges
// sujet du message :
$config_mail_sujet_new_demande = "APPLI CONGES - Demande de cong�s";
// corps du message : (il y aura syst�matiquement le nom de la personne demandeuse au d�but du message)
$config_mail_contenu_new_demande = " a solicit� une demande de cong�s dans l'application de gestion des cong�s.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas de saisie de nouveau conges par le responsable
// sujet du message :
$config_mail_sujet_valid_conges = "APPLI CONGES - Cong�s valid�";
// corps du message : (il y aura syst�matiquement le nom de la personne demandeuse au d�but du message)
$config_mail_contenu_valid_conges = " a enregistr�/valid� un cong�s pour vous dans l'application de gestion des cong�s.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas de refus d'une demande de conges par le responsable
// sujet du message :
$config_mail_sujet_refus_conges = "APPLI CONGES - Cong�s refus�";
// corps du message : (il y aura syst�matiquement le nom de la personne demandeuse au d�but du message)
$config_mail_contenu_refus_conges = " a refus� une demande de cong�s pour vous dans l'application de gestion des cong�s.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas d'annulation de conges par le responsable
// sujet du message :
$config_mail_sujet_annul_conges = "APPLI CONGES - Cong�s annul�";
// corps du message : (il y aura syst�matiquement le nom de la personne demandeuse au d�but du message)
$config_mail_contenu_annul_conges = " a annul� un de vos cong�s dans l'application de gestion des cong�s.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";


?>
