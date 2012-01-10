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
// SCRIPT DE MIGRATION DE LA VERSION 1.0 vers 1.1
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
	// 1 : Mise à jour de la table conges_users
	// 2 : Creation de la table conges_config 
	// 3 : Initialisation de la table `conges_config`
	// 4 : Mise a jour de la table `conges_config` avec les param de l'ancien fichier de config
	// 5 : Création de la table `conges_type_absence`
	// 6 : Insertion du contenu de la table `conges_type_absence`
	// 7 : Mise à jour de la table `conges_periode`
	// 8 : Création de la table `conges_solde_user`
	// 9 : Migration de données de la table conges_users vers `conges_solde_user`
	// 10 : Mise à jour de la table `conges_users`
	// 11 : Création de la table `conges_solde_edition`
	// 12 : Migration de données de la table conges_edition_papier vers `conges_solde_edition`
	// 13 : Mise à jour de la table `conges_edition_papier`
	
	include CONFIG_PATH .'config.php' ;
	
	if($DEBUG==FALSE)
	{
		// on lance les etape (fonctions) séquentiellement 
		e1_maj_1_table_conges_users( $DEBUG);
		e2_create_table_conges_config( $DEBUG);
		e3_insert_into_conges_config( $DEBUG);
		e4_maj_table_conges_config( $DEBUG);
		e5_create_table_conges_type_absence( $DEBUG);
		e6_insert_into_conges_type_absence( $DEBUG);
		e7_maj_table_conges_periode( $DEBUG);
		e8_create_table_conges_solde_user( $DEBUG);
		e9_insert_into_conges_solde_user( $DEBUG);
		e10_maj_2_table_conges_users( $DEBUG);
		e11_create_table_conges_solde_edition( $DEBUG);
		e12_insert_into_conges_solde_edition( $DEBUG);
		e13_maj_table_conges_edition_papier( $DEBUG);
		
		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=mise_a_jour.php?etape=4&version=$version&lang=$lang\">";
	}
	else
	{

		// on lance les etape (fonctions) séquentiellement :
		// avec un arret à la fin de chaque étape  
		
		$sub_etape=( (isset($_GET['sub_etape'])) ? $_GET['sub_etape'] : ( (isset($_POST['sub_etape'])) ? $_POST['sub_etape'] : 1 ) ) ;

		if($sub_etape==1) { e1_maj_1_table_conges_users( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=2&lang=$lang\">sub_etape 1  OK</a><br>\n"; }
		if($sub_etape==2) { e2_create_table_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=3&lang=$lang\">sub_etape 2  OK</a><br>\n"; }
		if($sub_etape==3) { e3_insert_into_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=4&lang=$lang\">sub_etape 3  OK</a><br>\n"; }
		if($sub_etape==4) { e4_maj_table_conges_config( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=5&lang=$lang\">sub_etape 4  OK</a><br>\n"; }
		if($sub_etape==5) { e5_create_table_conges_type_absence( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=6&lang=$lang\">sub_etape 5  OK</a><br>\n"; }
		if($sub_etape==6) { e6_insert_into_conges_type_absence( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=7&lang=$lang\">sub_etape 6  OK</a><br>\n"; }
		if($sub_etape==7) { e7_maj_table_conges_periode( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=8&lang=$lang\">sub_etape 7  OK</a><br>\n"; }
		if($sub_etape==8) { e8_create_table_conges_solde_user( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=9&lang=$lang\">sub_etape 8  OK</a><br>\n"; }
		if($sub_etape==9) { e9_insert_into_conges_solde_user( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=10&lang=$lang\">sub_etape 9  OK</a><br>\n"; }
		if($sub_etape==10) { e10_maj_2_table_conges_users( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=11&lang=$lang\">sub_etape 10  OK</a><br>\n"; }
		if($sub_etape==11) { e11_create_table_conges_solde_edition( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=12&lang=$lang\">sub_etape 11  OK</a><br>\n"; }
		if($sub_etape==12) { e12_insert_into_conges_solde_edition( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=13&lang=$lang\">sub_etape 12  OK</a><br>\n"; }
		if($sub_etape==13) { e13_maj_table_conges_edition_papier( $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=14&lang=$lang\">sub_etape 13  OK</a><br>\n"; }

		
		// on renvoit à la page mise_a_jour.php (là d'ou on vient)
		if($sub_etape==14) { echo "<a href=\"mise_a_jour.php?etape=4&version=$version\">upgrade_from_v1.0  OK</a><br>\n"; }
	}


/********************************************************************************************************/
/********************************************************************************************************/
/***   FONCTIONS   ***/
/********************************************************************************************************/



/**********************************************************/
/***   ETAPE 1 : mise à jour de la table conges_users   ***/
/**********************************************************/
// update de la table conges_users
function e1_maj_1_table_conges_users( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	
	// verif si champ u_see_all existe déja
	$result = SQL::query("SHOW COLUMNS FROM conges_users");
	while ($row = $result->fetch_assoc()) 
	{
		//print_r($row);
		if($row['Field']=="u_see_all")
			return ; // on sort de la fonction
	}

	// ajout du champ dans le table
	$sql_alter="ALTER TABLE `conges_users` ADD `u_see_all` ENUM( 'Y', 'N' ) DEFAULT 'N' NOT NULL AFTER `u_is_admin` " ;
	$result_alter = SQL::query($sql_alter);

	// mise à jour des users
	$sql_update=" UPDATE `conges_users` SET `u_see_all` = 'Y' WHERE `u_login` = 'conges' " ;
	$result_update = SQL::query($sql_update);
		

}


/***********************************************************/
/***   ETAPE 2 :  Création de la table `conges_config`   ***/
/***********************************************************/
function e2_create_table_conges_config( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	/*********************************************/
	// creation de la table `conges_config`
	$sql_create="CREATE TABLE `conges_config` (
				`conf_nom` varchar(100) BINARY NOT NULL default '',
  				`conf_valeur` varchar(200) BINARY NOT NULL default '',
  				`conf_groupe` VARCHAR(200) NOT NULL default '',
  				`conf_type` VARCHAR(200) NOT NULL default 'texte' ,
  				`conf_commentaire` text NOT NULL default '',
 				 PRIMARY KEY  (`conf_nom`)
				);" ;
	$result_create = SQL::query($sql_create);
}
		

/*****************************************************************/
/***   ETAPE 3 :  initialisation de la table `conges_config`   ***/
/*****************************************************************/
// initialisation de la table `conges_config`
function e3_insert_into_conges_config( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
		
	// verif si le fichier "data_conges_config_v1.1.sql" (qui contient les requetes INSERT)existe et est lisible ....
	// la verif a du etre faite en debut de la procedure d'install ...
	$result = execute_sql_file("sql/data_conges_config_v1.1.sql",  $DEBUG);
	
	if($DEBUG==TRUE)
		if($result==FALSE)
		{
			echo "erreur : e3_insert_into_conges_config<br>execute_sql_file(\"sql/data_conges_config_v1.1.sql\")...\n";
			exit;
		} 
}


/**********************************************************************************************************/
/***   ETAPE 4 : Mise a jour de la table `conges_config` avec les param de l'ancien fichier de config   ***/
/**********************************************************************************************************/
function e4_maj_table_conges_config( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

		/*********************************************/
		// Mise a jour de la table `conges_config` avec les param de l'ancien fichier de config
		
		// verif si le fichier 'config_old.php' existe et est lisible ....
		// la verif a du etre faite en debut de la procedure d'install ...
		// on lit l'ancien fichier de config
		include'config_old.php' ;
				
		// on update la table conges_config 
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$URL_ACCUEIL_CONGES' WHERE `conf_nom` = 'URL_ACCUEIL_CONGES' ;";
		$result = SQL::query($sql1);
		// si debug on ne teste pas tous les result mais seulement le premier ....
		
		
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_img_login' WHERE `conf_nom` = 'img_login' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_texte_img_login' WHERE `conf_nom` = 'texte_img_login' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_lien_img_login' WHERE `conf_nom` = 'lien_img_login' ;";
		$result = SQL::query($sql1);
		
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_titre_calendrier' WHERE `conf_nom` = 'titre_calendrier' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_titre_user_index' WHERE `conf_nom` = 'titre_user_index' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_titre_resp_index' WHERE `conf_nom` = 'titre_resp_index' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_titre_admin_index' WHERE `conf_nom` = 'titre_admin_index' ;";
		$result = SQL::query($sql1);
		
		if($config_auth==TRUE) {$config_auth="TRUE";} else {$config_auth="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_auth' WHERE `conf_nom` = 'auth' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_how_to_connect_user' WHERE `conf_nom` = 'how_to_connect_user' ;";
		$result = SQL::query($sql1);
		if($config_export_users_from_ldap==TRUE) {$config_export_users_from_ldap="TRUE";} else {$config_export_users_from_ldap="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_export_users_from_ldap' WHERE `conf_nom` = 'export_users_from_ldap' ;";
		$result = SQL::query($sql1);
		
		if($config_user_saisie_demande==TRUE) {$config_user_saisie_demande="TRUE";} else {$config_user_saisie_demande="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_user_saisie_demande' WHERE `conf_nom` = 'user_saisie_demande' ;";
		$result = SQL::query($sql1);
		if($config_user_affiche_calendrier==TRUE) {$config_user_affiche_calendrier="TRUE";} else {$config_user_affiche_calendrier="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_user_affiche_calendrier' WHERE `conf_nom` = 'user_affiche_calendrier' ;";
		$result = SQL::query($sql1);
		if($config_user_saisie_mission==TRUE) {$config_user_saisie_mission="TRUE";} else {$config_user_saisie_mission="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_user_saisie_mission' WHERE `conf_nom` = 'user_saisie_mission' ;";
		$result = SQL::query($sql1);
		if($config_user_ch_passwd==TRUE) {$config_user_ch_passwd="TRUE";} else {$config_user_ch_passwd="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_user_ch_passwd' WHERE `conf_nom` = 'user_ch_passwd' ;";
		$result = SQL::query($sql1);
		
		if($config_responsable_virtuel==TRUE) {$config_responsable_virtuel="TRUE";} else {$config_responsable_virtuel="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_responsable_virtuel' WHERE `conf_nom` = 'responsable_virtuel' ;";
		$result = SQL::query($sql1);
		if($config_resp_affiche_calendrier==TRUE) {$config_resp_affiche_calendrier="TRUE";} else {$config_resp_affiche_calendrier="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_resp_affiche_calendrier' WHERE `conf_nom` = 'resp_affiche_calendrier' ;";
		$result = SQL::query($sql1);
		if($config_resp_saisie_mission==TRUE) {$config_resp_saisie_mission="TRUE";} else {$config_resp_saisie_mission="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_resp_saisie_mission' WHERE `conf_nom` = 'resp_saisie_mission' ;";
		$result = SQL::query($sql1);
		if($config_resp_vertical_menu==TRUE) {$config_resp_vertical_menu="TRUE";} else {$config_resp_vertical_menu="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_resp_vertical_menu' WHERE `conf_nom` = 'resp_vertical_menu' ;";
		$result = SQL::query($sql1);
		
		if($config_admin_see_all==TRUE) {$config_admin_see_all="TRUE";} else {$config_admin_see_all="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_admin_see_all' WHERE `conf_nom` = 'admin_see_all' ;";
		$result = SQL::query($sql1);
		if($config_admin_change_passwd==TRUE) {$config_admin_change_passwd="TRUE";} else {$config_admin_change_passwd="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_admin_change_passwd' WHERE `conf_nom` = 'admin_change_passwd' ;";
		$result = SQL::query($sql1);
		
		if($config_mail_new_demande_alerte_resp==TRUE) {$config_mail_new_demande_alerte_resp="TRUE";} else {$config_mail_new_demande_alerte_resp="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_mail_new_demande_alerte_resp' WHERE `conf_nom` = 'mail_new_demande_alerte_resp' ;";
		$result = SQL::query($sql1);
		if($config_mail_valid_conges_alerte_user==TRUE) {$config_mail_valid_conges_alerte_user="TRUE";} else {$config_mail_valid_conges_alerte_user="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_mail_valid_conges_alerte_user' WHERE `conf_nom` = 'mail_valid_conges_alerte_user' ;";
		$result = SQL::query($sql1);
		if($config_mail_refus_conges_alerte_user==TRUE) {$config_mail_refus_conges_alerte_user="TRUE";} else {$config_mail_refus_conges_alerte_user="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_mail_refus_conges_alerte_user' WHERE `conf_nom` = 'mail_refus_conges_alerte_user' ;";
		$result = SQL::query($sql1);
		if($config_mail_annul_conges_alerte_user==TRUE) {$config_mail_annul_conges_alerte_user="TRUE";} else {$config_mail_annul_conges_alerte_user="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_mail_annul_conges_alerte_user' WHERE `conf_nom` = 'mail_annul_conges_alerte_user' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_serveur_smtp' WHERE `conf_nom` = 'serveur_smtp' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_where_to_find_user_email' WHERE `conf_nom` = 'where_to_find_user_email' ;";
		$result = SQL::query($sql1);
		
		if($config_samedi_travail==TRUE) {$config_samedi_travail="TRUE";} else {$config_samedi_travail="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_samedi_travail' WHERE `conf_nom` = 'samedi_travail' ;";
		$result = SQL::query($sql1);
		if($config_dimanche_travail==TRUE) {$config_dimanche_travail="TRUE";} else {$config_dimanche_travail="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_dimanche_travail' WHERE `conf_nom` = 'dimanche_travail' ;";
		$result = SQL::query($sql1);
		
		if($config_gestion_groupes==TRUE) {$config_gestion_groupes="TRUE";} else {$config_gestion_groupes="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_gestion_groupes' WHERE `conf_nom` = 'gestion_groupes' ;";
		$result = SQL::query($sql1);
		if($config_affiche_groupe_in_calendrier==TRUE) {$config_affiche_groupe_in_calendrier="TRUE";} else {$config_affiche_groupe_in_calendrier="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_affiche_groupe_in_calendrier' WHERE `conf_nom` = 'affiche_groupe_in_calendrier' ;";
		$result = SQL::query($sql1);
		
		if($config_editions_papier==TRUE) {$config_editions_papier="TRUE";} else {$config_editions_papier="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_editions_papier' WHERE `conf_nom` = 'editions_papier' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_texte_haut_edition_papier' WHERE `conf_nom` = 'texte_haut_edition_papier' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_texte_bas_edition_papier' WHERE `conf_nom` = 'texte_bas_edition_papier' ;";
		$result = SQL::query($sql1);
		
		//ATTENTION 
		// cette info ne sert plus dans php conges, mais set dans la suite de la migration : on la stoque donc ici !
		if($config_rtt_comme_conges==TRUE) {$config_rtt_comme_conges="TRUE";} else {$config_rtt_comme_conges="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_rtt_comme_conges' WHERE `conf_nom` = 'rtt_comme_conges' ;";
		$result = SQL::query($sql1);

		if($config_user_echange_rtt==TRUE) {$config_user_echange_rtt="TRUE";} else {$config_user_echange_rtt="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_user_echange_rtt' WHERE `conf_nom` = 'user_echange_rtt' ;";
		$result = SQL::query($sql1);

		if($config_affiche_bouton_calcul_nb_jours_pris==TRUE) {$config_affiche_bouton_calcul_nb_jours_pris="TRUE";} else {$config_affiche_bouton_calcul_nb_jours_pris="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_affiche_bouton_calcul_nb_jours_pris' WHERE `conf_nom` = 'affiche_bouton_calcul_nb_jours_pris' ;";
		$result = SQL::query($sql1);
		if($config_rempli_auto_champ_nb_jours_pris==TRUE) {$config_rempli_auto_champ_nb_jours_pris="TRUE";} else {$config_rempli_auto_champ_nb_jours_pris="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_rempli_auto_champ_nb_jours_pris' WHERE `conf_nom` = 'rempli_auto_champ_nb_jours_pris' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$duree_session' WHERE `conf_nom` = 'duree_session' ;";
		$result = SQL::query($sql1);
		if($config_verif_droits==TRUE) {$config_verif_droits="TRUE";} else {$config_verif_droits="FALSE";} ;
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_verif_droits' WHERE `conf_nom` = 'verif_droits' ;";
		$result = SQL::query($sql1);
		
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_stylesheet_file' WHERE `conf_nom` = 'stylesheet_file' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_bgcolor' WHERE `conf_nom` = 'bgcolor' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_bgimage' WHERE `conf_nom` = 'bgimage' ;";
		$result = SQL::query($sql1);
		$sql1 = "UPDATE `conges_config` SET `conf_valeur` = '$config_light_grey_bgcolor' WHERE `conf_nom` = 'light_grey_bgcolor' ;";
		$result = SQL::query($sql1);
		
}


/******************************************************************/
/***   ETAPE 5 : Création de la table `conges_type_absence`     ***/
/******************************************************************/
function e5_create_table_conges_type_absence( $DEBUG=FALSE)
{
	// creation de la table `conges_type_absence`
	$sql_create="CREATE TABLE `conges_type_absence` (
				  `ta_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
				  `ta_type` enum('conges','absence') NOT NULL default 'conges',
				  `ta_libelle` varchar(20) NOT NULL default '',
				  `ta_short_libelle` char(3) NOT NULL default '',
				  PRIMARY KEY  (`ta_id`)
				);" ;
	$result_create = SQL::query($sql_create);
}

/****************************************************************************/
/***   ETAPE 6 : Insertion du contenu de la table `conges_type_absence`   ***/
/****************************************************************************/
function e6_insert_into_conges_type_absence( $DEBUG=FALSE)
{
	// ajout des types d'absence de base dans la table `conges_type_absence`
	$sql_insert="INSERT INTO `conges_type_absence` VALUES (1, 'conges', 'congés payés', 'cp');" ;
	$result_insert = SQL::query($sql_insert);

	if(is_rtt_comme_conges()==TRUE)
	{
		$sql_insert="INSERT INTO `conges_type_absence` VALUES (2, 'conges', 'rtt', 'rtt');";
		$result_insert = SQL::query($sql_insert);
	}

	$sql_insert="INSERT INTO `conges_type_absence` VALUES (3, 'absence', 'formation', 'fo');";
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO `conges_type_absence` VALUES (4, 'absence', 'misson', 'mi');" ;
	$result_insert = SQL::query($sql_insert);

	$sql_insert="INSERT INTO `conges_type_absence` VALUES (5, 'absence', 'autre', 'ab');";
	$result_insert = SQL::query($sql_insert);

}

/******************************************************************/
/***   ETAPE 7 : Mise à jour de la table `conges_periode`       ***/
/******************************************************************/
function e7_maj_table_conges_periode( $DEBUG=FALSE)
{
	// modif de la table conges_periode existante avec les types d'absence !

	$sql_alter1="ALTER TABLE `conges_periode` ADD `new_type` INT( 2 ) UNSIGNED NOT NULL " ;
	$result_alter1 = SQL::query($sql_alter1);

	$sql_update1="UPDATE `conges_periode` SET `new_type` = '1' WHERE `p_type` = 'conges' " ;
	$result_update1 = SQL::query($sql_update1);
	
	$sql_update1="UPDATE `conges_periode` SET `new_type` = '2' WHERE `p_type` = 'rtt' " ;
	$result_update1 = SQL::query($sql_update1);
	
	$sql_update1="UPDATE `conges_periode` SET `new_type` = '3' WHERE `p_type` = 'formation' " ;
	$result_update1 = SQL::query($sql_update1);

	$sql_update1="UPDATE `conges_periode` SET `new_type` = '4' WHERE `p_type` = 'mission' " ;
	$result_update1 = SQL::query($sql_update1);

	$sql_update1="UPDATE `conges_periode` SET `new_type` = '5' WHERE `p_type` = 'autre' ";
	$result_update1 = SQL::query($sql_update1);

	$sql_alter2="ALTER TABLE `conges_periode` CHANGE `p_type` `p_type` INT( 2 ) UNSIGNED DEFAULT '1' NOT NULL  ";
	$result_alter2 = SQL::query($sql_alter2);

	$sql_update2="UPDATE `conges_periode` SET `p_type` = 1 WHERE `new_type` = 1 " ;
	$result_update2 = SQL::query($sql_update2);
	
	$sql_update2="UPDATE `conges_periode` SET `p_type` = 2 WHERE `new_type` = 2 " ;
	$result_update2 = SQL::query($sql_update2);

	$sql_update2="UPDATE `conges_periode` SET `p_type` = 3 WHERE `new_type` = 3 " ;
	$result_update2 = SQL::query($sql_update2);

	$sql_update2="UPDATE `conges_periode` SET `p_type` = 4 WHERE `new_type` = 4 " ;
	$result_update2 = SQL::query($sql_update2);

	$sql_update2="UPDATE `conges_periode` SET `p_type` = 5 WHERE `new_type` = 5 ";
	$result_update2 = SQL::query($sql_update2);

	$sql_alter3="ALTER TABLE `conges_periode` DROP `new_type`  ";
	$result_alter3 = SQL::query($sql_alter3);

}


/****************************************************************/
/***   ETAPE 8 :  Création de la table `conges_solde_user`    ***/
/****************************************************************/
function e8_create_table_conges_solde_user( $DEBUG=FALSE)
{
	// creation de la table `conges_solde_user`
	$sql_create="CREATE TABLE `conges_solde_user` (
				  `su_login` varchar(16) NOT NULL default '',
				  `su_abs_id` int(2) unsigned NOT NULL default '0',
				  `su_nb_an` decimal(4,2) NOT NULL default '0.00',
				  `su_solde` decimal(4,2) NOT NULL default '0.00'
				);" ;
	$result_create = SQL::query($sql_create);

}	

/*********************************************************************************************/
/***   ETAPE 9 :  Migration de données de la table conges_users vers `conges_solde_user`   ***/
/*********************************************************************************************/
function e9_insert_into_conges_solde_user( $DEBUG=FALSE)
{
	// id des type d'absence
	$id_conges=1 ;   // id du type d'asb dont le libelle est "conges"
	$id_rtt=2 ;      // id du type d'asb dont le libelle est "rtt"
	
	// recup des infos de conges_users
	$sql="SELECT u_login, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt FROM conges_users " .
			" WHERE u_login!='admin' AND u_login!='conges' ";
	$ReqLog = SQL::query($sql1) ;
	while ($resultat = $ReqLog->fetch_array())
	{
		$sql_login=$resultat['u_login'];
		$sql_nb_jours_an=$resultat['u_nb_jours_an'];
		$sql_solde_jours=$resultat['u_solde_jours'];
		$sql_nb_rtt_an=$resultat['u_nb_rtt_an'];
		$sql_solde_rtt=$resultat['u_solde_rtt'];
		
		// pour chaque user : insert du type conges (nb_an et solde) et insert du type rtt (nb_an et solde) (si rtt_comme conges)
		$sql_insert_cong="INSERT INTO conges_solde_user (su_login, su_abs_id, su_nb_an, su_solde) VALUES ('$sql_login', $id_conges, $sql_nb_jours_an, $sql_solde_jours) ";
		//echo "$sql_insert_cong<br>\n";
		$result_insert_cong = SQL::query($sql_insert_cong);
		
		if(is_rtt_comme_conges()==TRUE)
		{
			$sql_insert_rtt="INSERT INTO conges_solde_user (su_login, su_abs_id, su_nb_an, su_solde) VALUES ('$sql_login', $id_rtt, $sql_nb_rtt_an, $sql_solde_rtt) ";
			$result_insert_rtt = SQL::query($sql_insert_rtt);
		}
	}

}


/*************************************************************/
/***   ETAPE 10 : Mise à jour de la table `conges_users`   ***/
/*************************************************************/
function e10_maj_2_table_conges_users( $DEBUG=FALSE)
{
	// suppr des champs nb_an et solde pour conges et rtt de conges_users 
	$sql_alter1="ALTER TABLE conges_users DROP u_nb_jours_an  ";
	$result_alter1 = SQL::query($sql_alter1);

	$sql_alter2="ALTER TABLE conges_users DROP u_solde_jours  ";
	$result_alter2 = SQL::query($sql_alter2);

	$sql_alter3="ALTER TABLE conges_users DROP u_nb_rtt_an  ";
	$result_alter3 = SQL::query($sql_alter3);

	$sql_alter4="ALTER TABLE conges_users DROP u_solde_rtt  ";
	$result_alter4 = SQL::query($sql_alter4);

}


/*******************************************************************/
/***   ETAPE 11 : Création de la table `conges_solde_edition`    ***/
/*******************************************************************/
function e11_create_table_conges_solde_edition( $DEBUG=FALSE)
{
	// creation de la table `conges_solde_user`
	$sql_create="CREATE TABLE `conges_solde_edition` (
		`se_id_edition` INT( 11 ) NOT NULL ,
		`se_id_absence` INT( 2 ) NOT NULL ,
		`se_solde` DECIMAL( 4, 2 ) NOT NULL
		);" ;
	$result_create = SQL::query($sql_create);

}	


/**********************************************************************************************************/
/***   ETAPE 12 : Migration de données de la table conges_edition_papier vers `conges_solde_edition`    ***/
/**********************************************************************************************************/
function e12_insert_into_conges_solde_edition( $DEBUG=FALSE)
{
 	// id des type d'absence
	$id_conges=1 ;   // id du type d'asb dont le libelle est "conges"
	$id_rtt=2 ;      // id du type d'asb dont le libelle est "rtt"
	
	// recup des infos de conges_edition_papier
	$sql="SELECT  ep_id, ep_solde_jours, ep_solde_rtt FROM conges_edition_papier " ;
	$ReqLog = SQL::query($sql1) ;
	while ($resultat = $ReqLog->fetch_array())
	{
		$sql_ep_id=$resultat['ep_id'];
		$sql_ep_solde_jours=$resultat['ep_solde_jours'];
		$sql_ep_solde_rtt=$resultat['ep_solde_rtt'];
		
		// pour chaque edition : insert du solde conges et insert du solde rtt (si rtt_comme conges)
		$sql_insert_cong="INSERT INTO conges_solde_edition (se_id_edition, se_id_absence, se_solde) VALUES ($sql_ep_id, $id_conges, $sql_ep_solde_jours) ";
		//echo "$sql_insert_cong<br>\n";
		$result_insert_cong = SQL::query($sql_insert_cong);

		if(is_rtt_comme_conges()==TRUE)
		{
			$sql_insert_rtt="INSERT INTO conges_solde_edition (se_id_edition, se_id_absence, se_solde) VALUES ($sql_ep_id, $id_rtt, $sql_ep_solde_rtt) ";
			$result_insert_rtt = SQL::query($sql_insert_rtt);
		}
	}

}

/***********************************************************************/
/***   ETAPE 13 : Mise à jour de la table `conges_edition_papier`    ***/
/***********************************************************************/
function e13_maj_table_conges_edition_papier( $DEBUG=FALSE)
{
	// suppr des champs solde pour conges et rtt de conges_edition_papier 
	$sql_alter1="ALTER TABLE conges_edition_papier DROP ep_solde_jours  ";
	$result_alter1 = SQL::query($sql_alter1);

	$sql_alter2="ALTER TABLE conges_edition_papier DROP ep_solde_rtt  ";
	$result_alter2 = SQL::query($sql_alter2);

}






