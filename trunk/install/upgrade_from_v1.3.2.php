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
// SCRIPT DE MIGRATION DE LA VERSION 1.3.2 vers 1.4.0
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
	// 1 : mise à jour du champ login dans les tables (respect de la casse)

	include CONFIG_PATH .'dbconnect.php' ;

	if( !$DEBUG )
	{
		// on lance les etapes (fonctions) séquentiellement
		e1_insert_into_conges_config( $DEBUG);
		e2_create_table_jours_fermeture( $DEBUG);
		e3_alter_table_conges_periode( $DEBUG);
		e4_alter_tables_longueur_login( $DEBUG);
		e5_delete_from_conges_config( $DEBUG);
		e6_insert_into_conges_mail( $DEBUG);

		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=mise_a_jour.php?etape=4&version=$version&lang=$lang\">";
	}
	else
	{
		// on lance les etape (fonctions) séquentiellement :
		// avec un arret à la fin de chaque étape

		$sub_etape=( (isset($_GET['sub_etape'])) ? $_GET['sub_etape'] : ( (isset($_POST['sub_etape'])) ? $_POST['sub_etape'] : 0 ) ) ;

		if($sub_etape==0) { echo "<a href=\"$PHP_SELF?sub_etape=1&version=$version&lang=$lang\">start upgrade_from_v1.3.0</a><br>\n"; }
		if($sub_etape==1) { e1_insert_into_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=2&version=$version&lang=$lang\">sub_etape 1  OK</a><br>\n"; }
		if($sub_etape==2) { e2_create_table_jours_fermeture( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=3&version=$version&lang=$lang\">sub_etape 2  OK</a><br>\n"; }
		if($sub_etape==3) { e3_alter_table_conges_periode( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=4&version=$version&lang=$lang\">sub_etape 3  OK</a><br>\n"; }
		if($sub_etape==4) { e4_alter_tables_longueur_login( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=5&version=$version&lang=$lang\">sub_etape 4  OK</a><br>\n"; }
		if($sub_etape==5) { e5_delete_from_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=6&version=$version&lang=$lang\">sub_etape 5  OK</a><br>\n"; }
		if($sub_etape==6) { e6_insert_into_conges_mail( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=7&version=$version&lang=$lang\">sub_etape 6  OK</a><br>\n"; }

		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		if($sub_etape==7) { echo "<a href=\"mise_a_jour.php?etape=4&version=$version&lang=$lang\">upgrade_from_v1.3.2  OK</a><br>\n"; }
	}


/********************************************************************************************************/
/********************************************************************************************************/
/***   FONCTIONS   ***/
/********************************************************************************************************/



/*****************************************************************/
/***   ETAPE 1 : Ajout de paramètres dans  conges_config       ***/
/*****************************************************************/
function e1_insert_into_conges_config( $DEBUG=FALSE)
{

	$sql_insert_1="INSERT INTO `conges_config` VALUES ('fermeture_par_groupe', 'FALSE', '10_Gestion par groupes', 'boolean', 'config_comment_fermeture_par_groupe')";
	$result_insert_1 = SQL::query($sql_insert_1)  ;

	$sql_insert_2="INSERT INTO `conges_config` VALUES ('affiche_demandes_dans_calendrier', 'FALSE', '13_Divers', 'boolean', 'config_comment_affiche_demandes_dans_calendrier')";
	$result_insert_2 = SQL::query($sql_insert_2)  ;

	$sql_insert_3="INSERT INTO `conges_config` VALUES ('calcul_auto_jours_feries_france', 'FALSE', '13_Divers', 'boolean', 'config_comment_calcul_auto_jours_feries_france')";
	$result_insert_3 = SQL::query($sql_insert_3)  ;

	$sql_insert_4="INSERT INTO `conges_config` VALUES ('gestion_cas_absence_responsable', 'FALSE', '06_Responsable', 'boolean', 'config_comment_gestion_cas_absence_responsable')";
	$result_insert_4 = SQL::query($sql_insert_4)  ;

}


/******************************************************************/
/***   ETAPE 2 : Creation de la table conges_jours_fermeture   ***/
/******************************************************************/
function e2_create_table_jours_fermeture( $DEBUG=FALSE)
{

	$sql_create="CREATE TABLE `conges_jours_fermeture` (
				`jf_id` INT( 5 ) NOT NULL ,
				`jf_gid` INT( 11 ) NOT NULL DEFAULT '0',
				`jf_date` DATE NOT NULL
				) DEFAULT CHARSET=latin1 ";
	$result_create = SQL::query($sql_create);

}


/******************************************************************/
/***   ETAPE 3 : Modif de la table conges_periode   ***/
/******************************************************************/
function e3_alter_table_conges_periode( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql_alter_1=" ALTER TABLE `conges_periode` ADD `p_fermeture_id` INT ";
	$result_alter_1 = SQL::query($sql_alter_1)  ;

	$sql_alter_2=" ALTER TABLE `conges_periode` CHANGE `p_nb_jours` `p_nb_jours` DECIMAL( 5, 2 ) NOT NULL DEFAULT '0.00' ";
	$result_alter_2 = SQL::query($sql_alter_2)  ;

}


/******************************************************************/
/***   ETAPE 4 : Modif de la table conges_users   ***/
/******************************************************************/
function e4_alter_tables_longueur_login( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql_alter_1=" ALTER TABLE `conges_users` CHANGE `u_login` `u_login` VARBINARY( 32 ) NOT NULL , CHANGE `u_resp_login` `u_resp_login` VARBINARY( 32 ) NULL DEFAULT NULL";
	$result_alter_1 = SQL::query($sql_alter_1)  ;

	$sql_alter_2=" ALTER TABLE `conges_solde_user` CHANGE `su_login` `su_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_21 = SQL::query($sql_alter_2)  ;

	$sql_alter_3=" ALTER TABLE `conges_periode` CHANGE `p_login` `p_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_3 = SQL::query($sql_alter_3)  ;

	$sql_alter_4=" ALTER TABLE `conges_logs` CHANGE `log_user_login_par` `log_user_login_par` VARBINARY( 32 ) NOT NULL , CHANGE `log_user_login_pour` `log_user_login_pour` VARBINARY( 32 ) NOT NULL ";
	$result_alter_4 = SQL::query($sql_alter_4)  ;

	$sql_alter_5=" ALTER TABLE `conges_historique_ajout` CHANGE `ha_login` `ha_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_5 = SQL::query($sql_alter_5)  ;

	$sql_alter_6=" ALTER TABLE `conges_groupe_users` CHANGE `gu_login` `gu_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_6 = SQL::query($sql_alter_6)  ;

	$sql_alter_7=" ALTER TABLE `conges_groupe_resp` CHANGE `gr_login` `gr_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_7 = SQL::query($sql_alter_7)  ;

	$sql_alter_8=" ALTER TABLE `conges_groupe_grd_resp` CHANGE `ggr_login` `ggr_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_8 = SQL::query($sql_alter_8)  ;

	$sql_alter_9=" ALTER TABLE `conges_edition_papier` CHANGE `ep_login` `ep_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_9 = SQL::query($sql_alter_9)  ;

	$sql_alter_10=" ALTER TABLE `conges_echange_rtt` CHANGE `e_login` `e_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_10 = SQL::query($sql_alter_10)  ;

	$sql_alter_11=" ALTER TABLE `conges_artt` CHANGE `a_login` `a_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_11 = SQL::query($sql_alter_11)  ;

}
   

/***********************************************************************/
/***   ETAPE 5 : Suppression de paramètres dans  conges_config       ***/
/***********************************************************************/
function e5_delete_from_conges_config( $DEBUG=FALSE)
{

	$sql_insert_1="DELETE FROM `conges_config` WHERE `conf_nom` = 'resp_vertical_menu' ";
	$result_insert_1 = SQL::query($sql_insert_1)  ;

}


/*****************************************************************/
/***   ETAPE 6 : Ajout d'un type de mail dans conges_mail       ***/
/*****************************************************************/
function e6_insert_into_conges_mail( $DEBUG=FALSE)
{

	$sql_insert_1="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_new_demande_resp_absent', 'APPLI CONGES - Demande de congés', ' __SENDER_NAME__ a solicité une demande de congés dans l''application de gestion des congés.\r\n\r\nEn votre absence, cette demande a été transférée à votre (vos) propre(s) responsable(s)./\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.')";
	$result_insert_1 = SQL::query($sql_insert_1)  ;

}






