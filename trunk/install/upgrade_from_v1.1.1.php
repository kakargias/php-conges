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
// SCRIPT DE MIGRATION DE LA VERSION 1.1.1 vers 1.2
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
	// 1 : Ajout de paramètres dans  conges_config
	// 2 : Création de la table conges_mail
	// 3 : Ajout des definitions des mail dans  conges_mail
	// 4 : Alter de la table conges_edition_papier
	// 5 : Alter de la table conges_periode
	// 6 : Alter de la table conges_groupe
	// 7 : Création de la table conges_groupe_grd_resp
	// 8 : Modif des parametres de config dans conges_config
	
	include CONFIG_PATH .'dbconnect.php' ;
	
	if($DEBUG==FALSE)
	{
		// on lance les etape (fonctions) séquentiellement 
		e1_insert_into_conges_config( $DEBUG);
		e2_create_table_conges_mail( $DEBUG);
		e3_insert_into_conges_mail( $DEBUG);
		e4_alter_table_conges_edition_papier( $DEBUG);
		e5_alter_table_conges_periode( $DEBUG);
		e6_alter_table_conges_groupe( $DEBUG);
		e7_create_table_conges_groupe_grd_resp( $DEBUG);
		e8_delete_from_conges_config( $DEBUG);
		
		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=mise_a_jour.php?etape=4&version=$version&lang=$lang\">";
	}
	else
	{
	
		// on lance les etape (fonctions) séquentiellement :
		// avec un arret à la fin de chaque étape  
		
		$sub_etape=( (isset($_GET['sub_etape'])) ? $_GET['sub_etape'] : ( (isset($_POST['sub_etape'])) ? $_POST['sub_etape'] : 0 ) ) ;

		if($sub_etape==0) { echo "<a href=\"$PHP_SELF?sub_etape=1&version=$version&lang=$lang\">start upgrade_from_v1.1.1</a><br>\n"; }		
		if($sub_etape==1) { e1_insert_into_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=2&version=$version&lang=$lang\">sub_etape 1  OK</a><br>\n"; }
		if($sub_etape==2) { e2_create_table_conges_mail( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=3&version=$version&lang=$lang\">sub_etape 2  OK</a><br>\n"; }
		if($sub_etape==3) { e3_insert_into_conges_mail( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=4&version=$version&lang=$lang\">sub_etape 3  OK</a><br>\n"; }
		if($sub_etape==4) { e4_alter_table_conges_edition_papier( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=5&version=$version&lang=$lang\">sub_etape 4  OK</a><br>\n"; }
		if($sub_etape==5) { e5_alter_table_conges_periode( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=6&version=$version&lang=$lang\">sub_etape 5  OK</a><br>\n"; }
		if($sub_etape==6) { e6_alter_table_conges_groupe( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=7&version=$version&lang=$lang\">sub_etape 6  OK</a><br>\n"; }
		if($sub_etape==7) { e7_create_table_conges_groupe_grd_resp( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=8&version=$version&lang=$lang\">sub_etape 7  OK</a><br>\n"; }
		if($sub_etape==8) { e8_delete_from_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=9&version=$version&lang=$lang\">sub_etape 8  OK</a><br>\n"; }
		
		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		if($sub_etape==9) { echo "<a href=\"mise_a_jour.php?etape=4&version=$version&lang=$lang\">upgrade_from_v1.1.1  OK</a><br>\n"; }
	}


/********************************************************************************************************/
/********************************************************************************************************/
/***   FONCTIONS   ***/
/********************************************************************************************************/



/***********************************************************/
/***   ETAPE 1 : Ajout de paramètres dans  conges_config   ***/
/*************************************************************/
function e1_insert_into_conges_config( $DEBUG=FALSE)
{

	$sql_insert="INSERT INTO conges_config VALUES ('disable_saise_champ_nb_jours_pris', 'FALSE', '13_Divers', 'boolean', 'config_comment_disable_saise_champ_nb_jours_pris')";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO conges_config VALUES ('export_ical_vcal', 'TRUE', '13_Divers', 'boolean', 'config_comment_export_ical_vcal')";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO conges_config VALUES ('semaine_bgcolor', '#FFFFFF', '14_Présentation', 'hidden', 'config_comment_semaine_bgcolor')";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO conges_config VALUES ('week_end_bgcolor', '#BFBFBF', '14_Présentation', 'hidden', 'config_comment_week_end_bgcolor')";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO conges_config VALUES ('temps_partiel_bgcolor', '#FFFFC4', '14_Présentation', 'hidden', 'config_comment_temps_partiel_bgcolor')";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO conges_config VALUES ('conges_bgcolor', '#DEDEDE', '14_Présentation', 'hidden', 'config_comment_conges_bgcolor')";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO conges_config VALUES ('demande_conges_bgcolor', '#E7C4C4', '14_Présentation', 'hidden', 'config_comment_demande_conges_bgcolor')";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO conges_config VALUES ('absence_autre_bgcolor', '#D3FFB6', '14_Présentation', 'hidden', 'config_comment_absence_autre_bgcolor')";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO `conges_config` VALUES ('affiche_bouton_config_mail_pour_admin', 'FALSE', '07_Administrateur', 'boolean', 'config_comment_affiche_bouton_config_mail_pour_admin')";
	$result_insert = SQL::query($sql_insert);
		
	$sql_insert="INSERT INTO `conges_config` VALUES ('double_validation_conges', 'FALSE', '12_Fonctionnement de l\'Etablissement', 'boolean', 'config_comment_double_validation_conges')";
	$result_insert = SQL::query($sql_insert);
	
	$sql_insert="INSERT INTO `conges_config` VALUES ('mail_prem_valid_conges_alerte_user', 'FALSE', '08_Mail', 'boolean', 'config_comment_mail_prem_valid_conges_alerte_user')";
	$result_insert = SQL::query($sql_insert);
	
	$sql_insert="INSERT INTO `conges_config` VALUES ('affiche_date_traitement', 'FALSE', '13_Divers', 'boolean', 'config_comment_affiche_date_traitement')";
	$result_insert = SQL::query($sql_insert);
	
	
}
		

/****************************************************************/
/***   ETAPE 2 :  Création de la table conges_mail    ***/
/****************************************************************/
function e2_create_table_conges_mail( $DEBUG=FALSE)
{
	// creation de la table `conges_mail`
	$sql_create="CREATE TABLE `conges_mail` (
				`mail_nom` VARCHAR( 100 ) NOT NULL ,
				`mail_subject` TEXT NULL ,
				`mail_body` TEXT NULL ,
				UNIQUE KEY `mail_nom` (`mail_nom`)
				) ;" ;
	
	$result_create = SQL::query($sql_create);

}	

		
/***********************************************************/
/***   ETAPE 3 : Ajout des definitions des mail dans  conges_mail   ***/
/*************************************************************/
function e3_insert_into_conges_mail( $DEBUG=FALSE)
{

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_new_demande', 'APPLI CONGES - Demande de congés', ' __SENDER_NAME__ a solicité une demande de congés dans l''application de gestion des congés.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.')";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_valid_conges', 'APPLI CONGES - Congés accepté', ' __SENDER_NAME__ a enregistré/acceptéé un congés pour vous dans l''application de gestion des congés.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.');";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_refus_conges', 'APPLI CONGES - Congés refusé', ' __SENDER_NAME__ a refusé une demande de congés pour vous dans l''application de gestion des congés.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.');";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_annul_conges', 'APPLI CONGES - Congés annulé', ' __SENDER_NAME__ a annulé un de vos congés dans l''application de gestion des congés.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.');";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_prem_valid_conges', 'APPLI CONGES - Congés validé', ' __SENDER_NAME__ a validé (première validation) un congés pour vous dans l''application de gestion des congés.\r\n\Il doit maintenant être accepté en deuxième validation.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.');";
	$result_insert = SQL::query($sql_insert);

		
}


/****************************************************************/
/***   ETAPE 4 :  Alter de la table conges_edition_papier    ***/
/****************************************************************/
function e4_alter_table_conges_edition_papier( $DEBUG=FALSE)
{
	// alter de la table `conges_edition_papier`
	$sql_alter="ALTER TABLE `conges_edition_papier` (
				DROP `ep_solde_jours`,
  				DROP `ep_solde_rtt`;" ;
	
	$result_alter = SQL::query($sql_alter);

}	


/****************************************************************/
/***   ETAPE 5 :  Alter de la table conges_periode            ***/
/****************************************************************/
function e5_alter_table_conges_periode( $DEBUG=FALSE)
{

	// alter de la table `conges_periode`
	$sql_alter="ALTER TABLE `conges_periode` CHANGE `p_etat` `p_etat` ENUM( 'ok', 'valid', 'demande', 'ajout', 'refus', 'annul' )  NOT NULL DEFAULT 'demande' ;" ;
	$result_alter = SQL::query($sql_alter);

	$sql_alter="ALTER TABLE `conges_periode` ADD `p_date_demande` DATETIME NULL AFTER `p_motif_refus`, ADD `p_date_traitement` DATETIME NULL AFTER `p_date_demande` ;" ;
	$result_alter = SQL::query($sql_alter);
				
}	


/****************************************************************/
/***   ETAPE 6 :  Alter de la table conges_groupe            ***/
/****************************************************************/
function e6_alter_table_conges_groupe( $DEBUG=FALSE)
{
	// alter de la table `conges_groupe`
	$sql_alter="ALTER TABLE `conges_groupe` ADD `g_double_valid` ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N' ;" ;
	
	$result_alter = SQL::query($sql_alter);

}	


/****************************************************************/
/***   ETAPE 7 :  Création de la table conges_groupe_grd_resp    ***/
/****************************************************************/
function e7_create_table_conges_groupe_grd_resp( $DEBUG=FALSE)
{
	// creation de la table `conges_mail`
	$sql_create="CREATE TABLE `conges_groupe_grd_resp` (
  				`ggr_gid` int(11) NOT NULL default '0',
  				`ggr_login` varchar(16) binary NOT NULL default ''
				);" ;
	
	$result_create = SQL::query($sql_create);

}	

		
/*************************************************************/
/***   ETAPE 8 :  modif des parametres de config dans conges_config   ***/
/*************************************************************/
function e8_delete_from_conges_config( $DEBUG=FALSE)
{
	// suppression des parametres de config obsoletes dans conges_config
	
	$sql_update="DELETE FROM conges_config WHERE conf_nom='titre_page_accueil'" ;
	$result_update = SQL::query($sql_update);
		
		
}



