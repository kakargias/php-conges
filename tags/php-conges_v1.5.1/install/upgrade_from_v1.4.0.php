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

//appel de PHP-IDS que si version de php > 5.1.2
if(phpversion() > "5.1.2") { include("../controle_ids.php") ;}

/*******************************************************************/
// SCRIPT DE MIGRATION DE LA VERSION 1.4.0 vers 1.4.1
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
		e2_drop_table_historique_ajout($mysql_link, $DEBUG);

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
		if($sub_etape==2) { e2_drop_table_historique_ajout($mysql_link, $DEBUG); echo "<a href=\"$PHP_SELF?sub_etape=3&version=$version&lang=$lang\">sub_etape 2  OK</a><br>\n"; }

		mysql_close($mysql_link);
		// on renvoit � la page mise_a_jour.php (l� d'ou on vient)
		if($sub_etape==3) { echo "<a href=\"mise_a_jour.php?etape=4&version=$version&lang=$lang\">upgrade_from_v1.4.0  OK</a><br>\n"; }
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

	$sql_insert_1="INSERT INTO `conges_config` VALUES ('fermeture_bgcolor', '#7B9DE6', '14_Presentation', 'hidden', 'config_comment_fermeture_bgcolor')";
	$result_insert_1 = mysql_query($sql_insert_1, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_2="INSERT INTO `conges_config` VALUES ('texte_page_login', '', '02_PAGE D\'AUTENTIFICATION', 'texte', 'config_comment_texte_page_login')";
	$result_insert_2 = mysql_query($sql_insert_2, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

	$sql_insert_3="INSERT INTO `conges_config` VALUES ('solde_toujours_positif', 'FALSE', '12_Fonctionnement de l\'Etablissement', 'boolean', 'config_comment_solde_toujours_positif')";
	$result_insert_3 = mysql_query($sql_insert_3, $mysql_link) or die("erreur : e1_insert_into_conges_config<br>\n".mysql_error($mysql_link)) ;

}

function e2_drop_table_historique_ajout($mysql_link, $DEBUG=FALSE)
{

	if( test_create_table($mysql_link, $DEBUG)==TRUE)
	{
		if( test_drop_table($mysql_link, $DEBUG)==TRUE)
		{
			$sql_drop_1="DROP TABLE `conges_historique_ajout`";
			$result_drop_1 = mysql_query($sql_drop_1, $mysql_link) or die("erreur : e2_drop_table_historique_ajout<br>\n".mysql_error($mysql_link)) ;
		}
	}
}








?>
