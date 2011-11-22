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

include("../controle_ids.php") ;

/*******************************************************************/
// SCRIPT DE MIGRATION DE LA VERSION 1.1.1 vers 1.2
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
	// 1 : Ajout de param�tres dans  conges_config
	// 2 : Cr�ation de la table conges_mail
	// 3 : Ajout des definitions des mail dans  conges_mail
	// 4 : Alter de la table conges_edition_papier
	// 5 : Alter de la table conges_periode
	// 6 : Alter de la table conges_groupe
	// 7 : Cr�ation de la table conges_groupe_grd_resp
	// 8 : Modif des parametres de config dans conges_config
	
	include("../dbconnect.php") ;
	
	if($DEBUG==FALSE)
	{
		$mysql_link = mysql_connexion($mysql_serveur, $mysql_user, $mysql_pass, $mysql_database);
		// on lance les etape (fonctions) s�quentiellement 
		e1_insert_into_conges_config($mysql_link, $DEBUG);
		e2_create_table_conges_mail($mysql_link, $DEBUG);
		e3_insert_into_conges_mail($mysql_link, $DEBUG);
		e4_alter_table_conges_edition_papier($mysql_link, $DEBUG);
		e5_alter_table_conges_periode($mysql_link, $DEBUG);
		e6_alter_table_conges_groupe($mysql_link, $DEBUG);
		e7_create_table_conges_groupe_grd_resp($mysql_link, $DEBUG);
		e8_delete_from_conges_config($mysql_link, $DEBUG);
		
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

		if($sub_etape==0) { echo "<a href=\"$PHP_SELF?sub_etape=1&version=$version&lang=$lang\">start upgrade_from_v1.1.1</a><br>\n"; }		
		if($sub_etape==1) { e1_insert_into_conges_config($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=2&version=$version&lang=$lang\">sub_etape 1  OK</a><br>\n"; }
		if($sub_etape==2) { e2_create_table_conges_mail($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=3&version=$version&lang=$lang\">sub_etape 2  OK</a><br>\n"; }
		if($sub_etape==3) { e3_insert_into_conges_mail($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=4&version=$version&lang=$lang\">sub_etape 3  OK</a><br>\n"; }
		if($sub_etape==4) { e4_alter_table_conges_edition_papier($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=5&version=$version&lang=$lang\">sub_etape 4  OK</a><br>\n"; }
		if($sub_etape==5) { e5_alter_table_conges_periode($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=6&version=$version&lang=$lang\">sub_etape 5  OK</a><br>\n"; }
		if($sub_etape==6) { e6_alter_table_conges_groupe($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=7&version=$version&lang=$lang\">sub_etape 6  OK</a><br>\n"; }
		if($sub_etape==7) { e7_create_table_conges_groupe_grd_resp($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=8&version=$version&lang=$lang\">sub_etape 7  OK</a><br>\n"; }
		if($sub_etape==8) { e8_delete_from_conges_config($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=9&version=$version&lang=$lang\">sub_etape 8  OK</a><br>\n"; }
		
		mysql_close($mysql_link);
		// on renvoit � la page mise_a_jour.php (l� d'ou on vient)
		if($sub_etape==9) { echo "<a href=\"mise_a_jour.php?etape=4&version=$version&lang=$lang\">upgrade_from_v1.1.1  OK</a><br>\n"; }
	}


/********************************************************************************************************/
/********************************************************************************************************/
/***   FONCTIONS   ***/
/********************************************************************************************************/



/***********************************************************/
/***   ETAPE 1 : Ajout de param�tres dans  conges_config   ***/
/*************************************************************/
function e1_insert_into_conges_config($mysql_link, $DEBUG=FALSE)
{

	$sql_insert="INSERT INTO conges_config VALUES ('disable_saise_champ_nb_jours_pris', 'FALSE', '13_Divers', 'boolean', 'config_comment_disable_saise_champ_nb_jours_pris')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO conges_config VALUES ('export_ical_vcal', 'TRUE', '13_Divers', 'boolean', 'config_comment_export_ical_vcal')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO conges_config VALUES ('semaine_bgcolor', '#FFFFFF', '14_Pr�sentation', 'hidden', 'config_comment_semaine_bgcolor')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO conges_config VALUES ('week_end_bgcolor', '#BFBFBF', '14_Pr�sentation', 'hidden', 'config_comment_week_end_bgcolor')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO conges_config VALUES ('temps_partiel_bgcolor', '#FFFFC4', '14_Pr�sentation', 'hidden', 'config_comment_temps_partiel_bgcolor')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO conges_config VALUES ('conges_bgcolor', '#DEDEDE', '14_Pr�sentation', 'hidden', 'config_comment_conges_bgcolor')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO conges_config VALUES ('demande_conges_bgcolor', '#E7C4C4', '14_Pr�sentation', 'hidden', 'config_comment_demande_conges_bgcolor')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO conges_config VALUES ('absence_autre_bgcolor', '#D3FFB6', '14_Pr�sentation', 'hidden', 'config_comment_absence_autre_bgcolor')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO `conges_config` VALUES ('affiche_bouton_config_mail_pour_admin', 'FALSE', '07_Administrateur', 'boolean', 'config_comment_affiche_bouton_config_mail_pour_admin')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;
		
	$sql_insert="INSERT INTO `conges_config` VALUES ('double_validation_conges', 'FALSE', '12_Fonctionnement de l\'Etablissement', 'boolean', 'config_comment_double_validation_conges')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;
	
	$sql_insert="INSERT INTO `conges_config` VALUES ('mail_prem_valid_conges_alerte_user', 'FALSE', '08_Mail', 'boolean', 'config_comment_mail_prem_valid_conges_alerte_user')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;
	
	$sql_insert="INSERT INTO `conges_config` VALUES ('affiche_date_traitement', 'FALSE', '13_Divers', 'boolean', 'config_comment_affiche_date_traitement')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;
	
	
}
		

/****************************************************************/
/***   ETAPE 2 :  Cr�ation de la table conges_mail    ***/
/****************************************************************/
function e2_create_table_conges_mail($mysql_link, $DEBUG=FALSE)
{
	// creation de la table `conges_mail`
	$sql_create="CREATE TABLE `conges_mail` (
				`mail_nom` VARCHAR( 100 ) NOT NULL ,
				`mail_subject` TEXT NULL ,
				`mail_body` TEXT NULL ,
				UNIQUE KEY `mail_nom` (`mail_nom`)
				) TYPE = MYISAM ;" ;
	
	if($DEBUG==FALSE)
		$result_create = mysql_query($sql_create, $mysql_link);
	else
		$result_create = mysql_query($sql_create, $mysql_link) or die("erreur : e2_create_table_conges_mail<br>\n".mysql_error()) ;

}	

		
/***********************************************************/
/***   ETAPE 3 : Ajout des definitions des mail dans  conges_mail   ***/
/*************************************************************/
function e3_insert_into_conges_mail($mysql_link, $DEBUG=FALSE)
{

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_new_demande', 'APPLI CONGES - Demande de cong�s', ' __SENDER_NAME__ a solicit� une demande de cong�s dans l''application de gestion des cong�s.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.')";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e3_insert_into_conges_mail<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_valid_conges', 'APPLI CONGES - Cong�s accept�', ' __SENDER_NAME__ a enregistr�/accept�� un cong�s pour vous dans l''application de gestion des cong�s.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.');";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e3_insert_into_conges_mail<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_refus_conges', 'APPLI CONGES - Cong�s refus�', ' __SENDER_NAME__ a refus� une demande de cong�s pour vous dans l''application de gestion des cong�s.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.');";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e3_insert_into_conges_mail<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_annul_conges', 'APPLI CONGES - Cong�s annul�', ' __SENDER_NAME__ a annul� un de vos cong�s dans l''application de gestion des cong�s.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.');";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e3_insert_into_conges_mail<br>\n".mysql_error($mysql_link)) ;

	$sql_insert="INSERT INTO `conges_mail` (`mail_nom`, `mail_subject`, `mail_body`) VALUES ('mail_prem_valid_conges', 'APPLI CONGES - Cong�s valid�', ' __SENDER_NAME__ a valid� (premi�re validation) un cong�s pour vous dans l''application de gestion des cong�s.\r\n\Il doit maintenant �tre accept� en deuxi�me validation.\r\n\r\nMerci de consulter votre application php_conges : __URL_ACCUEIL_CONGES__\r\n\r\n-------------------------------------------------------------------------------------------------------\r\nCeci est un message automatique.');";
	if($DEBUG==FALSE)
		$result_insert = mysql_query($sql_insert, $mysql_link);
	else
		$result_insert = mysql_query($sql_insert, $mysql_link) or die("erreur : e3_insert_into_conges_mail<br>\n".mysql_error($mysql_link)) ;

		
}


/****************************************************************/
/***   ETAPE 4 :  Alter de la table conges_edition_papier    ***/
/****************************************************************/
function e4_alter_table_conges_edition_papier($mysql_link, $DEBUG=FALSE)
{
	// alter de la table `conges_edition_papier`
	$sql_alter="ALTER TABLE `conges_edition_papier` (
				DROP `ep_solde_jours`,
  				DROP `ep_solde_rtt`;" ;
	
	if($DEBUG==FALSE)
		$result_alter = mysql_query($sql_alter, $mysql_link);
	else
		$result_alter = mysql_query($sql_alter, $mysql_link) or die("erreur : e4_alter_table_conges_edition_papier<br>\n".mysql_error()) ;

}	


/****************************************************************/
/***   ETAPE 5 :  Alter de la table conges_periode            ***/
/****************************************************************/
function e5_alter_table_conges_periode($mysql_link, $DEBUG=FALSE)
{
	// alter de la table `conges_periode`
	$sql_alter="ALTER TABLE `conges_periode` CHANGE `p_etat` `p_etat` ENUM( 'ok', 'valid', 'demande', 'ajout', 'refus', 'annul' )  NOT NULL DEFAULT 'demande' ;" ;
	if($DEBUG==FALSE)
		$result_alter = mysql_query($sql_alter, $mysql_link);
	else
		$result_alter = mysql_query($sql_alter, $mysql_link) or die("erreur : e5_alter_table_conges_periode<br>\n".mysql_error()) ;

	$sql_alter="ALTER TABLE `conges_periode` ADD `p_date_demande` DATETIME NULL AFTER `p_motif_refus`, ADD `p_date_traitement` DATETIME NULL AFTER `p_date_demande` ;" ;
	if($DEBUG==FALSE)
		$result_alter = mysql_query($sql_alter, $mysql_link);
	else
		$result_alter = mysql_query($sql_alter, $mysql_link) or die("erreur : e5_alter_table_conges_periode<br>\n".mysql_error()) ;
				
}	


/****************************************************************/
/***   ETAPE 6 :  Alter de la table conges_groupe            ***/
/****************************************************************/
function e6_alter_table_conges_groupe($mysql_link, $DEBUG=FALSE)
{
	// alter de la table `conges_groupe`
	$sql_alter="ALTER TABLE `conges_groupe` ADD `g_double_valid` ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N' ;" ;
	
	if($DEBUG==FALSE)
		$result_alter = mysql_query($sql_alter, $mysql_link);
	else
		$result_alter = mysql_query($sql_alter, $mysql_link) or die("erreur : e6_alter_table_conges_groupe<br>\n".mysql_error()) ;

}	


/****************************************************************/
/***   ETAPE 7 :  Cr�ation de la table conges_groupe_grd_resp    ***/
/****************************************************************/
function e7_create_table_conges_groupe_grd_resp($mysql_link, $DEBUG=FALSE)
{
	// creation de la table `conges_mail`
	$sql_create="CREATE TABLE `conges_groupe_grd_resp` (
  				`ggr_gid` int(11) NOT NULL default '0',
  				`ggr_login` varchar(16) binary NOT NULL default ''
				) TYPE=MyISAM;" ;
	
	if($DEBUG==FALSE)
		$result_create = mysql_query($sql_create, $mysql_link);
	else
		$result_create = mysql_query($sql_create, $mysql_link) or die("erreur : e7_create_table_conges_groupe_grd_resp<br>\n".mysql_error()) ;

}	

		
/*************************************************************/
/***   ETAPE 8 :  modif des parametres de config dans conges_config   ***/
/*************************************************************/
function e8_delete_from_conges_config($mysql_link, $DEBUG=FALSE)
{
	// suppression des parametres de config obsoletes dans conges_config
	
	$sql_update="DELETE FROM conges_config WHERE conf_nom='titre_page_accueil'" ;
	if($DEBUG==FALSE)
		$result_update = mysql_query($sql_update, $mysql_link);
	else
		$result_update = mysql_query($sql_update, $mysql_link) or die("erreur : e8_delete_from_conges_config<br>\n".mysql_error($mysql_link)) ;
		
		
}


?>
