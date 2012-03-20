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

//appel de PHP-IDS que si version de php > 5.1.2
if(phpversion() > "5.1.2") { include("../controle_ids.php") ;}

/*******************************************************************/
// SCRIPT DE MIGRATION DE LA VERSION 1.4.2 vers 1.5.0
/*******************************************************************/
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("fonctions_install.php") ;

$PHP_SELF=$_SERVER['PHP_SELF'];

$DEBUG=FALSE;
//$DEBUG=TRUE;

$version = (isset($_GET['version']) ? $_GET['version'] : (isset($_POST['version']) ? $_POST['version'] : "")) ;
$lang = (isset($_GET['lang']) ? $_GET['lang'] : (isset($_POST['lang']) ? $_POST['lang'] : "")) ;

	// résumé des étapes :
	// 1 : mise à jour du champ login dans les tables (respect de la casse)

	include("../dbconnect.php") ;

	if($DEBUG==FALSE)
	{
		$mysql_link = mysql_connexion($mysql_serveur, $mysql_user, $mysql_pass, $mysql_database);
		// on lance les etapes (fonctions) séquentiellement
		e1_create_table_conges_appli($mysql_link, $DEBUG);
		e2_insert_into_conges_appli($mysql_link, $DEBUG);
		e3_delete_from_table_conges_config($mysql_link, $DEBUG);
		e4_alter_table_conges_users($mysql_link, $DEBUG);
		e5_insert_into_conges_config($mysql_link, $DEBUG);
		e6_alter_table_conges_solde_user($mysql_link, $DEBUG);
		e7_alter_tables_taille_login($mysql_link, $DEBUG);

		mysql_close($mysql_link);
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=mise_a_jour.php?etape=4&version=$version&lang=$lang\">";
	}
	else
	{
		$mysql_link = mysql_connexion($mysql_serveur, $mysql_user, $mysql_pass, $mysql_database);

		// on lance les etape (fonctions) séquentiellement :
		// avec un arret à la fin de chaque étape

		$sub_etape=( (isset($_GET['sub_etape'])) ? $_GET['sub_etape'] : ( (isset($_POST['sub_etape'])) ? $_POST['sub_etape'] : 0 ) ) ;

		if($sub_etape==0) { echo "<a href=\"$PHP_SELF?sub_etape=1&version=$version&lang=$lang\">start upgrade_from_v1.4.2</a><br>\n"; }
		if($sub_etape==1) { e1_create_table_conges_appli($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=2&version=$version&lang=$lang\">sub_etape 1  OK</a><br>\n"; }
		if($sub_etape==2) { e2_insert_into_conges_appli($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=3&version=$version&lang=$lang\">sub_etape 2  OK</a><br>\n"; }
		if($sub_etape==3) { e3_delete_from_table_conges_config($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=4&version=$version&lang=$lang\">sub_etape 3  OK</a><br>\n"; }
		if($sub_etape==4) { e4_alter_table_conges_users($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=5&version=$version&lang=$lang\">sub_etape 4  OK</a><br>\n"; }
		if($sub_etape==5) { e5_insert_into_conges_config($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=6&version=$version&lang=$lang\">sub_etape 5  OK</a><br>\n"; }
		if($sub_etape==6) { e6_alter_table_conges_solde_user($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=7&version=$version&lang=$lang\">sub_etape 6  OK</a><br>\n"; }
		if($sub_etape==7) { e7_alter_tables_taille_login($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=8&version=$version&lang=$lang\">sub_etape 7  OK</a><br>\n"; }
		
		mysql_close($mysql_link);
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		if($sub_etape==8) { echo "<a href=\"mise_a_jour.php?etape=4&version=$version&lang=$lang\">upgrade_from_v1.4.2  OK</a><br>\n"; }
	}


/********************************************************************************************************/
/********************************************************************************************************/
/***   FONCTIONS   ***/
/********************************************************************************************************/



/******************************************************************/
/***   ETAPE 1 : Creation de la table conges_appli              ***/
/******************************************************************/
function e1_create_table_conges_appli($mysql_link, $DEBUG=FALSE)
{

	$sql_create="CREATE TABLE IF NOT EXISTS `conges_appli` (
  					`appli_variable` varchar(100) binary NOT NULL default '',
  					`appli_valeur` varchar(200) binary NOT NULL default '',
  					PRIMARY KEY  (`appli_variable`)
					) TYPE=MyISAM DEFAULT CHARSET=latin1; ";
	if($DEBUG==FALSE)
		$result_create = mysql_query($sql_create, $mysql_link);
	else
		$result_create = mysql_query($sql_create, $mysql_link) or die("erreur : e1_create_table_conges_appli<br>\n".mysql_error($mysql_link)) ;

}


/*****************************************************************/
/***   ETAPE 2 : Ajout de paramètres dans  conges_appli       ***/
/*****************************************************************/
function e2_insert_into_conges_appli($mysql_link, $DEBUG=FALSE)
{

	$sql_insert_1="INSERT INTO `conges_appli` VALUES ('num_exercice', '1')";
	$result_insert_1 = mysql_query($sql_insert_1, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_2="INSERT INTO `conges_appli` VALUES ('date_limite_reliquats', '0')";
	$result_insert_2 = mysql_query($sql_insert_2, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_3="INSERT INTO `conges_appli` VALUES ('semaine_bgcolor', '#FFFFFF')";
	$result_insert_3 = mysql_query($sql_insert_3, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_4="INSERT INTO `conges_appli` VALUES ('week_end_bgcolor', '#BFBFBF')";
	$result_insert_4 = mysql_query($sql_insert_4, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_5="INSERT INTO `conges_appli` VALUES ('temps_partiel_bgcolor', '#FFFFC4')";
	$result_insert_5 = mysql_query($sql_insert_5, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_6="INSERT INTO `conges_appli` VALUES ('conges_bgcolor', '#DEDEDE')";
	$result_insert_6 = mysql_query($sql_insert_6, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_7="INSERT INTO `conges_appli` VALUES ('demande_conges_bgcolor', '#E7C4C4')";
	$result_insert_7 = mysql_query($sql_insert_7, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_8="INSERT INTO `conges_appli` VALUES ('absence_autre_bgcolor', '#D3FFB6')";
	$result_insert_8 = mysql_query($sql_insert_8, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_9="INSERT INTO `conges_appli` VALUES ('fermeture_bgcolor', '#7B9DE6')";
	$result_insert_9 = mysql_query($sql_insert_9, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

}


/**********************************************************************/
/***   ETAPE 3 : Suppression de paramètres dans conges_config       ***/
/**********************************************************************/
function e3_delete_from_table_conges_config($mysql_link, $DEBUG=FALSE)
{

	$sql_delete_1="DELETE FROM conges_config WHERE conf_type = 'hidden' ";
	$result_delete_1 = mysql_query($sql_delete_1, $mysql_link) or die("erreur : e3_delete_from_table_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_delete_2="DELETE FROM conges_config WHERE conf_nom = 'rtt_comme_conges' ";
	$result_delete_2 = mysql_query($sql_delete_2, $mysql_link) or die("erreur : e3_delete_from_table_conges_config<br>\n".mysql_error($mysql_link)) ;
}


/******************************************************************/
/***   ETAPE 4 : Modif de la table conges_users   ***/
/******************************************************************/
function e4_alter_table_conges_users($mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql_alter_1=" ALTER TABLE `conges_users` ADD `u_num_exercice` INT(2) NOT NULL DEFAULT '0' ";
	$result_alter_1 = mysql_query($sql_alter_1, $mysql_link) or die("erreur : e4_alter_table_conges_users<br>\n".mysql_error($mysql_link)) ;

}

/*****************************************************************/
/***   ETAPE 5 : Ajout de paramètres dans  conges_config       ***/
/*****************************************************************/
function e5_insert_into_conges_config($mysql_link, $DEBUG=FALSE)
{

	$sql_insert_1="INSERT INTO `conges_config` VALUES ('autorise_reliquats_exercice', 'TRUE', '12_Fonctionnement de l\'Etablissement', 'boolean', 'config_comment_autorise_reliquats_exercice')";
	$result_insert_1 = mysql_query($sql_insert_1, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_2="INSERT INTO `conges_config` VALUES ('nb_maxi_jours_reliquats', '0', '12_Fonctionnement de l\'Etablissement', 'texte', 'config_comment_nb_maxi_jours_reliquats')";
	$result_insert_2 = mysql_query($sql_insert_2, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_3="INSERT INTO `conges_config` VALUES ('jour_mois_limite_reliquats', '0', '12_Fonctionnement de l\'Etablissement', 'texte', 'config_comment_jour_mois_limite_reliquats')";
	$result_insert_3 = mysql_query($sql_insert_3, $mysql_link) or die("erreur : e2_insert_into_conges_appli<br>\n".mysql_error($mysql_link)) ;


}


/******************************************************************/
/***   ETAPE 6 : Modif de la table conges_solde_user   ***/
/******************************************************************/
function e6_alter_table_conges_solde_user($mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql_alter_1=" ALTER TABLE `conges_solde_user` ADD `su_reliquat` DECIMAL( 4, 2 ) NOT NULL DEFAULT '0' ";
	$result_alter_1 = mysql_query($sql_alter_1, $mysql_link) or die("erreur : e6_alter_table_conges_solde_user<br>\n".mysql_error($mysql_link)) ;

}


/*********************************************************************/
/***   ETAPE 7 : Modif de la taille max du login dans les tables   ***/
/*********************************************************************/
function e7_alter_tables_taille_login($mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql_alter_1=" ALTER TABLE `conges_artt` CHANGE `a_login` `a_login` VARBINARY( 99 ) NOT NULL ";
	$result_alter_1 = mysql_query($sql_alter_1, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_2=" ALTER TABLE `conges_echange_rtt` CHANGE `e_login` `e_login` VARBINARY( 99 ) NOT NULL ";
	$result_alter_2 = mysql_query($sql_alter_2, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ; 

	$sql_alter_3=" ALTER TABLE `conges_edition_papier` CHANGE `ep_login` `ep_login` VARBINARY( 99 ) NOT NULL ";
	$result_alter_3 = mysql_query($sql_alter_3, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ; 

	$sql_alter_4=" ALTER TABLE `conges_groupe_grd_resp` CHANGE `ggr_login` `ggr_login` VARBINARY( 99 ) NOT NULL ";
	$result_alter_4 = mysql_query($sql_alter_4, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ;  

	$sql_alter_5=" ALTER TABLE `conges_groupe_resp` CHANGE `gr_login` `gr_login` VARBINARY( 99 ) NOT NULL ";
	$result_alter_5 = mysql_query($sql_alter_5, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_6=" ALTER TABLE `conges_groupe_users` CHANGE `gu_login` `gu_login` VARBINARY( 99 ) NOT NULL ";
	$result_alter_6 = mysql_query($sql_alter_6, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_7=" ALTER TABLE `conges_logs` CHANGE `log_user_login_par` `log_user_login_par` VARBINARY( 99 ) NOT NULL , CHANGE `log_user_login_pour` `log_user_login_pour` VARBINARY( 99 ) NOT NULL ";
	$result_alter_7 = mysql_query($sql_alter_7, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ; 
 
	$sql_alter_8=" ALTER TABLE `conges_periode` CHANGE `p_login` `p_login` VARBINARY( 99 ) NOT NULL ";
	$result_alter_8 = mysql_query($sql_alter_8, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ;  

	$sql_alter_9=" ALTER TABLE `conges_solde_user` CHANGE `su_login` `su_login` VARBINARY( 99 ) NOT NULL ";
	$result_alter_9 = mysql_query($sql_alter_9, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ;
 
	$sql_alter_10=" ALTER TABLE `conges_users` CHANGE `u_login` `u_login` VARBINARY( 99 ) NOT NULL , CHANGE `u_resp_login` `u_resp_login` VARBINARY( 99 ) NULL DEFAULT NULL ";
	$result_alter_10 = mysql_query($sql_alter_10, $mysql_link) or die("erreur : e7_alter_tables_taille_login<br>\n".mysql_error($mysql_link)) ;
	 
}



?>
