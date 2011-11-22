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

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("fonctions_install.php") ;
	
$PHP_SELF=$_SERVER['PHP_SELF'];

$DEBUG=FALSE;
//$DEBUG=TRUE;

//recup de la langue
$lang=(isset($_GET['lang']) ? $_GET['lang'] : ((isset($_POST['lang'])) ? $_POST['lang'] : "") ) ;
$tab_lang_file = glob("lang/lang_".$lang."_*.php");  
if($DEBUG==TRUE) { echo "lang = $lang # fichier de langue = ".$tab_lang_file[0]."<br>\n"; }
include($tab_lang_file[0]) ;

if($DEBUG==TRUE) { echo "SESSION = <br>\n"; print_r($_SESSION); echo "<br><br>\n"; }

	
	echo "<html>\n<head>\n";
	echo "<TITLE> PHP_CONGES : Installation : </TITLE>\n</head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";	
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
		
	echo "<body text=\"#000000\" bgcolor=\"#597c98\" link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" >\n";
	
	// affichage du titre
	echo "<center>\n";
	echo "<br><H1><img src=\"../img/tux_config_32x32.png\" width=\"32\" height=\"32\" border=\"0\" title=\"".$_SESSION['lang']['install_install_phpconges']."\" alt=\"".$_SESSION['lang']['install_install_phpconges']."\"> ".$_SESSION['lang']['install_install_titre']."</H1>\n";
	echo "<br><br>\n";
		
	lance_install($lang, $DEBUG); 
	
	echo "<br><br>";
	echo "<center>\n";
	
	echo "</body>\n</html>\n";


/*****************************************************************************/
/*   FONCTIONS   */

// install la nouvelle version dans une database vide ... et config
function lance_install($lang, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	
	include("../dbconnect.php") ;
	include("../version.php") ;
	$mysql_link = mysql_connexion($mysql_serveur, $mysql_user, $mysql_pass, $mysql_database);
	
	//verif si create / alter table possible !!!
	if(test_create_table($mysql_link, $DEBUG) == FALSE)
	{
		echo "<font color=\"red\"><b>CREATE TABLE</b> ".$_SESSION['lang']['install_impossible_sur_db']." <b>$mysql_database</b> (".$_SESSION['lang']['install_verif_droits_mysql']." <b>$mysql_user</b>)...</font><br> \n";
		echo "<br>".$_SESSION['lang']['install_puis']." ...<br>\n";
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_redo']."\">\n";
		echo "</form>\n";
	}
	elseif(test_drop_table($mysql_link, $DEBUG) == FALSE)
	{
		echo "<font color=\"red\"><b>DROP TABLE</b> ".$_SESSION['lang']['install_impossible_sur_db']." <b>$mysql_database</b> (".$_SESSION['lang']['install_verif_droits_mysql']." <b>$mysql_user</b>)...</font><br> \n";
		echo "<br>".$_SESSION['lang']['install_puis']." ...<br>\n";
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_redo']."\">\n";
		echo "</form>\n";
	}
	else
	{
		//on execute le scrip [nouvelle vesion].sql qui crée et initialise les tables 
		$result = execute_sql_file("sql/php_conges_v1.2.1.sql", $mysql_link, $DEBUG);
		
		
		/*************************************/
		// FIN : mise à jour de la "installed_version" et de la langue dans la table conges_config
		$sql_update_version="UPDATE conges_config SET conf_valeur = '$config_php_conges_version' WHERE conf_nom='installed_version' ";
		$result_update_version = mysql_query($sql_update_version, $mysql_link) or die (mysql_error());

		$sql_update_lang="UPDATE conges_config SET conf_valeur = '$lang' WHERE conf_nom='lang' ";
		$result_update_lang = mysql_query($sql_update_lang, $mysql_link) or die (mysql_error());
		
		$tab_url=explode("/", $_SERVER['HTTP_REFERER']);
		$url_accueil="";
		for($i=0; $i<count($tab_url)-3; $i++)
		{
			$url_accueil=$url_accueil.$tab_url[$i]."/" ;  // on prend l'url complet sans le /install/install.php à la fin
		}
		$url_accueil=$url_accueil.$tab_url[$i] ;  // on prend l'url complet sans le /install/install.php à la fin
		$sql_update_lang="UPDATE conges_config SET conf_valeur = '$url_accueil' WHERE conf_nom='URL_ACCUEIL_CONGES' ";
		$result_update_lang = mysql_query($sql_update_lang, $mysql_link) or die (mysql_error());
		
		
		/*************************************/
		// on propose la page de config ....
		echo "<br><br><h2>".$_SESSION['lang']['install_ok']." !</h2><br>\n";
		
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"1; URL=../config/\">";
/*
		$_SESSION['config']=init_config_tab();      // on initialise le tableau des variables de config
		
		echo "<h2>".$_SESSION['lang']['install_vous_pouvez_maintenant']." :</h2>\n";
		echo "<h3>\n";
		echo "<table border=\"0\">\n";
		echo "<tr><td>-> <a href=\"configure.php?lang=$lang\">".$_SESSION['lang']['install_config_appli']."</a></td></tr>\n";
		echo "<tr><td>-> <a href=\"config_type_absence.php?lang=$lang\">".$_SESSION['lang']['install_config_types_abs']."</a></td></tr>";
		echo "<tr><td>-> <a href=\"".$_SESSION['config']['URL_ACCUEIL_CONGES']."\">".$_SESSION['lang']['install_acceder_appli']."</a></td></tr>";
		echo "</table>\n";
		echo "</h3><br><br>\n";
*/
	}
}


?>
