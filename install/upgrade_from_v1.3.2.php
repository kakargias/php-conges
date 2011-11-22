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


/*******************************************************************/
// SCRIPT DE MIGRATION DE LA VERSION 1.3.2 vers 1.4.0
/*******************************************************************/
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("fonctions_install.php") ;

$PHP_SELF=$_SERVER['PHP_SELF'];

$DEBUG=FALSE;
//$DEBUG=TRUE;

$version = (isset($_GET['version']) ? $_GET['version'] : (isset($_POST['version']) ? $_POST['version'] : "")) ;
$lang = (isset($_GET['lang']) ? $_GET['lang'] : (isset($_POST['lang']) ? $_POST['lang'] : "")) ;

	// r�sum� des �tapes :
	// 1 : mise � jour du champ login dans les tables (respect de la casse)

	include("../dbconnect.php") ;

	if($DEBUG==FALSE)
	{
		$mysql_link = mysql_connexion($mysql_serveur, $mysql_user, $mysql_pass, $mysql_database);
		// on lance les etapes (fonctions) s�quentiellement
		e1_insert_into_conges_config($mysql_link, $DEBUG);
		e2_create_table_jours_fermeture($mysql_link, $DEBUG);
		e3_alter_table_conges_periode($mysql_link, $DEBUG);
		e4_alter_tables_longueur_login($mysql_link, $DEBUG);
		e5_delete_from_conges_config($mysql_link, $DEBUG);
		e6_insert_into_conges_mail($mysql_link, $DEBUG);

		mysql_close($mysql_link);
		// on renvoit � la page mise_a_jour.php (l� d'ou on vient)
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=mise_a_jour.php?etape=4&version=$version&lang=$lang\">";
	}
	else
	{
		$mysql_link = mysql_connexion($mysql_serveur, $mysql_user, $mysql_pass, $mysql_database);

		// on lance les etape (fonctions) s�quentiellement :
		// avec un arret � la fin de chaque �tape

		$sub_etape=( (isset($_GET['sub_etape'])) ? $_GET['sub_etape'] : ( (isset($_POST['sub_etape'])) ? $_POST['sub_etape'] : 0 ) ) ;

		if($sub_etape==0) { echo "<a href=\"$PHP_SELF?sub_etape=1&version=$version&lang=$lang\">start upgrade_from_v1.3.0</a><br>\n"; }
		if($sub_etape==1) { e1_insert_into_conges_config($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=2&version=$version&lang=$lang\">sub_etape 1  OK</a><br>\n"; }
		if($sub_etape==2) { e2_create_table_jours_fermeture($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=3&version=$version&lang=$lang\">sub_etape 2  OK</a><br>\n"; }
		if($sub_etape==3) { e3_alter_table_conges_periode($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=4&version=$version&lang=$lang\">sub_etape 3  OK</a><br>\n"; }
		if($sub_etape==4) { e4_alter_tables_longueur_login($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=5&version=$version&lang=$lang\">sub_etape 4  OK</a><br>\n"; }
		if($sub_etape==5) { e5_delete_from_conges_config($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=6&version=$version&lang=$lang\">sub_etape 5  OK</a><br>\n"; }
		if($sub_etape==6) { e6_insert_into_conges_mail($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=7&version=$version&lang=$lang\">sub_etape 6  OK</a><br>\n"; }

		mysql_close($mysql_link);
		// on renvoit � la page mise_a_jour.php (l� d'ou on vient)
		if($sub_etape==7) { echo "<a href=\"mise_a_jour.php?etape=4&version=$version&lang=$lang\">upgrade_from_v1.3.2  OK</a><br>\n"; }
	}


/********************************************************************************************************/
/********************************************************************************************************/
/***   FONCTIONS   ***/
/********************************************************************************************************/



/*****************************************************************/
/***   ETAPE 1 : Ajout de param�tres dans  conges_config       ***/
/*****************************************************************/
function e1_insert_into_conges_config($mysql_link, $DEBUG=FALSE)
{

	$sql_insert_1="INSERT INTO `conges_config` VALUES ('fermeture_par_groupe', 'FALSE', '10_Gestion par groupes', 'boolean', 'config_comment_fermeture_par_groupe')";
	$result_insert_1 = mysql_query($sql_insert_1, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_2="INSERT INTO `conges_config` VALUES ('affiche_demandes_dans_calendrier', 'FALSE', '13_Divers', 'boolean', 'config_comment_affiche_demandes_dans_calendrier')";
	$result_insert_2 = mysql_query($sql_insert_2, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_3="INSERT INTO `conges_config` VALUES ('calcul_auto_jours_feries_france', 'FALSE', '13_Divers', 'boolean', 'config_comment_calcul_auto_jours_feries_france')";
	$result_insert_3 = mysql_query($sql_insert_3, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_4="INSERT INTO `conges_config` VALUES ('gestion_cas_absence_responsable', 'FALSE', '06_Responsable', 'boolean', 'config_comment_gestion_cas_absence_responsable')";
	$result_insert_4 = mysql_query($sql_insert_4, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

}


/******************************************************************/
/***   ETAPE 2 : Creation de la table conges_jours_fermeture   ***/
/******************************************************************/
function e2_create_table_jours_fermeture($mysql_link, $DEBUG=FALSE)
{

	$sql_create="CREATE TABLE `conges_jours_fermeture` (
				`jf_id` INT( 5 ) NOT NULL ,
				`jf_gid` INT( 11 ) NOT NULL DEFAULT '0',
				`jf_date` DATE NOT NULL
				) TYPE=MyISAM DEFAULT CHARSET=latin1 ";
	if($DEBUG==FALSE)
		$result_create = mysql_query($sql_create, $mysql_link);
	else
		$result_create = mysql_query($sql_create, $mysql_link) or die("erreur : e2_create_table_jours_fermeture<br>\n".mysql_error($mysql_link)) ;

}


/******************************************************************/
/***   ETAPE 3 : Modif de la table conges_periode   ***/
/******************************************************************/
function e3_alter_table_conges_periode($mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql_alter_1=" ALTER TABLE `conges_periode` ADD `p_fermeture_id` INT ";
	$result_alter_1 = mysql_query($sql_alter_1, $mysql_link) or die("erreur : e3_alter_table_conges_periode<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_2=" ALTER TABLE `conges_periode` CHANGE `p_nb_jours` `p_nb_jours` DECIMAL( 5, 2 ) NOT NULL DEFAULT '0.00' ";
	$result_alter_2 = mysql_query($sql_alter_2, $mysql_link) or die("erreur : e3_alter_table_conges_periode<br>\n".mysql_error($mysql_link)) ;

}


/******************************************************************/
/***   ETAPE 4 : Modif de la table conges_users   ***/
/******************************************************************/
function e4_alter_tables_longueur_login($mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql_alter_1=" ALTER TABLE `conges_users` CHANGE `u_login` `u_login` VARBINARY( 32 ) NOT NULL , CHANGE `u_resp_login` `u_resp_login` VARBINARY( 32 ) NULL DEFAULT NULL";
	$result_alter_1 = mysql_query($sql_alter_1, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_2=" ALTER TABLE `conges_solde_user` CHANGE `su_login` `su_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_21 = mysql_query($sql_alter_2, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_3=" ALTER TABLE `conges_periode` CHANGE `p_login` `p_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_3 = mysql_query($sql_alter_3, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_4=" ALTER TABLE `conges_logs` CHANGE `log_user_login_par` `log_user_login_par` VARBINARY( 32 ) NOT NULL , CHANGE `log_user_login_pour` `log_user_login_pour` VARBINARY( 32 ) NOT NULL ";
	$result_alter_4 = mysql_query($sql_alter_4, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_5=" ALTER TABLE `conges_historique_ajout` CHANGE `ha_login` `ha_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_5 = mysql_query($sql_alter_5, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_6=" ALTER TABLE `conges_groupe_users` CHANGE `gu_login` `gu_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_6 = mysql_query($sql_alter_6, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_7=" ALTER TABLE `conges_groupe_resp` CHANGE `gr_login` `gr_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_7 = mysql_query($sql_alter_7, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_8=" ALTER TABLE `conges_groupe_grd_resp` CHANGE `ggr_login` `ggr_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_8 = mysql_query($sql_alter_8, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_9=" ALTER TABLE `conges_edition_papier` CHANGE `ep_login` `ep_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_9 = mysql_query($sql_alter_9, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_10=" ALTER TABLE `conges_echange_rtt` CHANGE `e_login` `e_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_10 = mysql_query($sql_alter_10, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

	$sql_alter_11=" ALTER TABLE `conges_artt` CHANGE `a_login` `a_login` VARBINARY( 32 ) NOT NULL ";
	$result_alter_11 = mysql_query($sql_alter_11, $mysql_link) or die("erreur : e4_alter_tables_longueur_login<br>\n".mysql_error($mysql_link)) ;

}
   

/***********************************************************************/
/***   ETAPE 5 : Suppression de param�tres dans  conges_config       ***/
/***********************************************************************/
function e5_delete_from_conges_config($mysql_link, $DEBUG=FALSE)
{

	$sql_insert_1="DELETE FROM `conges_config` WHERE `conf_nom` = 'resp_vertical_menu' ";
	$result_insert_1 = mysql_query($sql_insert_1, $mysql_link) or die("erreur : e5_delete_from_conges_config<br>\n".mysql_error($mysql_link)) ;

}


/*****************************************************************/
/***   ETAPE 6 : Ajout d'un type de mail dans conges_mail       ***/
/*****************************************************************/
function e6_insert_into_conges_mail($mysql_link, $DEBUG=FALSE)
{

	$sql_insert_1="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_new_demande_resp_absent', 'APPLI CONGES - Demande de cong�s', ' __SENDER_NAME__ a solicit� une demande de cong�s dans l''application de gestion des cong�s.\r\n\r\nEn votre absence, cette demande a �t� transf�r�e � votre (vos) propre(s) responsable(s)./\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.')";
	$result_insert_1 = mysql_query($sql_insert_1, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

}





?>
