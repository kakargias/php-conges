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
// SCRIPT DE MIGRATION DE LA VERSION 1.3.0 vers 1.3.1
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
	
	if($DEBUG==FALSE)
	{
		// on lance les etapes (fonctions) séquentiellement 
		e1_alter_login_dans_tables( $DEBUG);
		e2_alter_table_conges_solde_user( $DEBUG);
		e3_insert_into_conges_config( $DEBUG);
		
		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=mise_a_jour.php?etape=4&version=$version&lang=$lang\">";
	}
	else
	{
		// on lance les etape (fonctions) séquentiellement :
		// avec un arret à la fin de chaque étape  
		
		$sub_etape=( (isset($_GET['sub_etape'])) ? $_GET['sub_etape'] : ( (isset($_POST['sub_etape'])) ? $_POST['sub_etape'] : 0 ) ) ;

		if($sub_etape==0) { echo "<a href=\"$PHP_SELF?sub_etape=1&version=$version&lang=$lang\">start upgrade_from_v1.3.0</a><br>\n"; }		
		if($sub_etape==1) { e1_alter_login_dans_tables( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=2&version=$version&lang=$lang\">sub_etape 1  OK</a><br>\n"; }
		if($sub_etape==2) { e2_alter_table_conges_solde_user( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=3&version=$version&lang=$lang\">sub_etape 2  OK</a><br>\n"; }
		if($sub_etape==3) { e3_insert_into_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=4&version=$version&lang=$lang\">sub_etape 3  OK</a><br>\n"; }
		
		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		if($sub_etape==4) { echo "<a href=\"mise_a_jour.php?etape=4&version=$version&lang=$lang\">upgrade_from_v1.3.0  OK</a><br>\n"; }
	}


/********************************************************************************************************/
/********************************************************************************************************/
/***   FONCTIONS   ***/
/********************************************************************************************************/



/*****************************************************************/
/***   ETAPE 1 : mise à jour du champ login dans les tables (respect de la casse)   ***/
/*****************************************************************/
function e1_alter_login_dans_tables( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	
	$sql_alter_1=" ALTER TABLE `conges_users` CHANGE `u_login` `u_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_1 = SQL::query($sql_alter_1)  ;

	$sql_alter_2=" ALTER TABLE `conges_artt` CHANGE `a_login` `a_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_2 = SQL::query($sql_alter_2)  ;

	$sql_alter_3=" ALTER TABLE `conges_echange_rtt` CHANGE `e_login` `e_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_3 = SQL::query($sql_alter_3)  ;

	$sql_alter_4=" ALTER TABLE `conges_edition_papier` CHANGE `ep_login` `ep_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_4 = SQL::query($sql_alter_4)  ;

	$sql_alter_5=" ALTER TABLE `conges_groupe_grd_resp` CHANGE `ggr_login` `ggr_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_5 = SQL::query($sql_alter_5)  ;

	$sql_alter_6=" ALTER TABLE `conges_groupe_resp` CHANGE `gr_login` `gr_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_6 = SQL::query($sql_alter_6)  ;

	$sql_alter_7=" ALTER TABLE `conges_groupe_users` CHANGE `gu_login` `gu_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_7 = SQL::query($sql_alter_7)  ;

	$sql_alter_8=" ALTER TABLE `conges_historique_ajout` CHANGE `ha_login` `ha_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_8 = SQL::query($sql_alter_8)  ;

	$sql_alter_9=" ALTER TABLE `conges_logs` CHANGE `log_user_login_par` `log_user_login_par` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_9 = SQL::query($sql_alter_9)  ;

	$sql_alter_10=" ALTER TABLE `conges_logs` CHANGE `log_user_login_pour` `log_user_login_pour` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_10 = SQL::query($sql_alter_10)  ;

	$sql_alter_11=" ALTER TABLE `conges_periode` CHANGE `p_login` `p_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_11 = SQL::query($sql_alter_11)  ;

	$sql_alter_12=" ALTER TABLE `conges_solde_user` CHANGE `su_login` `su_login` VARCHAR( 16 ) CHARACTER SET binary NOT NULL ";
	$result_alter_12 = SQL::query($sql_alter_12)  ;

}


/************************************************************************************/
/***   ETAPE 2 : ajout d'un index sur 2 colonnes dans la table conges_sold_user   ***/
/************************************************************************************/
function e2_alter_table_conges_solde_user( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	
	$sql_alter_1="  ALTER TABLE `conges_solde_user` ADD PRIMARY KEY ( `su_login` , `su_abs_id` ) ";
	$result_alter_1 = SQL::query($sql_alter_1)  ;

}


/*************************************************************/
/***   ETAPE 3 : Ajout de paramètres dans  conges_config   ***/
/*************************************************************/
function e3_insert_into_conges_config( $DEBUG=FALSE)
{

	$sql_insert="INSERT INTO `conges_config` VALUES ('interdit_modif_demande', 'FALSE', '13_Divers', 'boolean', 'config_comment_interdit_modif_demande')";
	$result_insert = SQL::query($sql_insert)  ;
	

}





