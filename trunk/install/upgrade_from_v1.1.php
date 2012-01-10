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

define('_PHP_CONGES', 1);
define('ROOT_PATH', '../');
include ROOT_PATH . 'define.php';
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

/*******************************************************************/
// SCRIPT DE MIGRATION DE LA VERSION 1.1 vers 1.1.1
/*******************************************************************/

include ROOT_PATH .'fonctions_conges.php' ;
include INCLUDE_PATH .'fonction.php';
include'fonctions_install.php' ;
	
	$PHP_SELF=$_SERVER['PHP_SELF'];

$DEBUG=FALSE;
//$DEBUG=TRUE;

$version = (isset($_GET['version']) ? $_GET['version'] : (isset($_POST['version']) ? $_POST['version'] : "")) ;
$lang = (isset($_GET['lang']) ? $_GET['lang'] : (isset($_POST['lang']) ? $_POST['lang'] : "")) ;

	// résumé des étapes :
	// 1 : Mise à jour de la table conges_config
	// 2 : ajout de la langue dans  conges_config 
	
	include CONFIG_PATH .'config.php' ;
	
	if($DEBUG==FALSE)
	{
		// on lance les etape (fonctions) séquentiellement 
		e1_maj_table_conges_config( $DEBUG);
		e2_insert_into_conges_config( $DEBUG);
		e3_update_into_conges_config( $DEBUG);
		
		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=mise_a_jour.php?etape=4&version=$version&lang=$lang\">";
	}
	else
	{
		// on lance les etape (fonctions) séquentiellement :
		// avec un arret à la fin de chaque étape  
		
		$sub_etape=( (isset($_GET['sub_etape'])) ? $_GET['sub_etape'] : ( (isset($_POST['sub_etape'])) ? $_POST['sub_etape'] : 1 ) ) ;

		if($sub_etape==1) { e1_maj_table_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=2&version=$version&lang=$lang\">sub_etape 1  OK</a><br>\n"; }
		if($sub_etape==2) { e2_insert_into_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=3&version=$version&lang=$lang\">sub_etape 2  OK</a><br>\n"; }
		if($sub_etape==3) { e3_update_into_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=4&version=$version&lang=$lang\">sub_etape 3  OK</a><br>\n"; }

		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		if($sub_etape==4) { echo "<a href=\"mise_a_jour.php?etape=4&version=$version&lang=$lang\">upgrade_from_v1.1  OK</a><br>\n"; }
	}


/********************************************************************************************************/
/********************************************************************************************************/
/***   FONCTIONS   ***/
/********************************************************************************************************/



/***********************************************************/
/***   ETAPE 1 : mise à jour de la table conges_config   ***/
/***********************************************************/
function e1_maj_table_conges_config( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	
	// mise à jour des param 00_version
	$sql_update=" UPDATE conges_config SET `conf_groupe` = '00_php_conges'  WHERE `conf_groupe` = '00_version' " ;
	$result_update = SQL::query($sql_update);
		

}


/*************************************************************/
/***   ETAPE 2 :  Ajout de la langue dans  conges_config   ***/
/*************************************************************/
function e2_insert_into_conges_config( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql_insert="INSERT INTO conges_config VALUES ('lang', 'fr', '00_php_conges', 'enum=fr/test', 'config_comment_lang')" ;
	$result_insert = SQL::query($sql_insert);
}
		

/*************************************************************/
/***   ETAPE 3 :  modif des commentaires des parametres de config dans conges_config   ***/
/*************************************************************/
function e3_update_into_conges_config( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	// modif des commentaires des parametres de config dans conges_config
	
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_installed_version' WHERE conf_nom='installed_version' " ;
	$result_update = SQL::query($sql_update);

	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_lang' WHERE conf_nom='lang'" ;
	$result_update = SQL::query($sql_update);

	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_URL_ACCUEIL_CONGES' WHERE conf_nom='URL_ACCUEIL_CONGES'" ;
	$result_update = SQL::query($sql_update);

	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_img_login' WHERE conf_nom='img_login'" ;
	$result_update = SQL::query($sql_update);

	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_texte_img_login' WHERE conf_nom='texte_img_login'" ;
	$result_update = SQL::query($sql_update);

	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_lien_img_login' WHERE conf_nom='lien_img_login'" ;
	$result_update = SQL::query($sql_update);

	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_titre_calendrier' WHERE conf_nom='titre_calendrier'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_titre_user_index' WHERE conf_nom='titre_user_index'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_titre_resp_index' WHERE conf_nom='titre_resp_index'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_titre_admin_index' WHERE conf_nom='titre_admin_index'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_auth' WHERE conf_nom='auth'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_how_to_connect_user' WHERE conf_nom='how_to_connect_user'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_export_users_from_ldap' WHERE conf_nom='export_users_from_ldap'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_user_saisie_demande' WHERE conf_nom='user_saisie_demande'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_user_affiche_calendrier' WHERE conf_nom='user_affiche_calendrier'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_user_saisie_mission' WHERE conf_nom='user_saisie_mission'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_user_ch_passwd' WHERE conf_nom='user_ch_passwd'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_responsable_virtuel' WHERE conf_nom='responsable_virtuel'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_resp_affiche_calendrier' WHERE conf_nom='resp_affiche_calendrier'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_resp_saisie_mission' WHERE conf_nom='resp_saisie_mission'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_resp_vertical_menu' WHERE conf_nom='resp_vertical_menu'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_admin_see_all' WHERE conf_nom='admin_see_all'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_admin_change_passwd' WHERE conf_nom='admin_change_passwd'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_affiche_bouton_config_pour_admin' WHERE conf_nom='affiche_bouton_config_pour_admin'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_affiche_bouton_config_absence_pour_admin' WHERE conf_nom='affiche_bouton_config_absence_pour_admin'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_mail_new_demande_alerte_resp' WHERE conf_nom='mail_new_demande_alerte_resp'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_mail_valid_conges_alerte_user' WHERE conf_nom='mail_valid_conges_alerte_user'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_mail_refus_conges_alerte_user' WHERE conf_nom='mail_refus_conges_alerte_user'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_mail_annul_conges_alerte_user' WHERE conf_nom='mail_annul_conges_alerte_user'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_serveur_smtp' WHERE conf_nom='serveur_smtp'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_where_to_find_user_email' WHERE conf_nom='where_to_find_user_email'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_samedi_travail' WHERE conf_nom='samedi_travail'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_dimanche_travail' WHERE conf_nom='dimanche_travail'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_gestion_groupes' WHERE conf_nom='gestion_groupes'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_affiche_groupe_in_calendrier' WHERE conf_nom='affiche_groupe_in_calendrier'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_editions_papier' WHERE conf_nom='editions_papier'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_texte_haut_edition_papier' WHERE conf_nom='texte_haut_edition_papier'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_texte_bas_edition_papier' WHERE conf_nom='texte_bas_edition_papier'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_rtt_comme_conges' WHERE conf_nom='rtt_comme_conges'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_user_echange_rtt' WHERE conf_nom='user_echange_rtt'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_affiche_bouton_calcul_nb_jours_pris' WHERE conf_nom='affiche_bouton_calcul_nb_jours_pris'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_rempli_auto_champ_nb_jours_pris' WHERE conf_nom='rempli_auto_champ_nb_jours_pris'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_duree_session' WHERE conf_nom='duree_session'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_verif_droits' WHERE conf_nom='verif_droits'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_stylesheet_file' WHERE conf_nom='stylesheet_file'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_bgcolor' WHERE conf_nom='bgcolor'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_bgimage' WHERE conf_nom='bgimage'" ;
	$result_update = SQL::query($sql_update);
		
	$sql_update="UPDATE conges_config SET conf_commentaire = 'config_comment_light_grey_bgcolor' WHERE conf_nom='light_grey_bgcolor'" ;
	$result_update = SQL::query($sql_update);
		
		
}
		





