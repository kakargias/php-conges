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
	
	// recup des parametres
	$action = (isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : "")) ;
	$version = (isset($_GET['version']) ? $_GET['version'] : (isset($_POST['version']) ? $_POST['version'] : "")) ;
	$etape = (isset($_GET['etape']) ? $_GET['etape'] : (isset($_POST['etape']) ? $_POST['etape'] : 0 )) ;
	
	if($DEBUG==TRUE) { echo "action = $action :: version = $version :: etape = $etape<br>\n";}
	
	if($version == 0)  // la version à mettre à jour dans le formulaire de index.php n'a pas été choisie : renvoit sur le formulaire
	{
		if($DEBUG==FALSE)
			echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=index.php\">";
		else
			echo "<a href=\"index.php\">la version à mettre à jour n'a pas été choisie</a><br>\n";
		exit;
	}

	echo "<html>\n<head>\n";
	echo "<TITLE> PHP_CONGES : Mise a Jour : </TITLE>\n</head>\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
		
	echo "<body text=\"#000000\" bgcolor=\"#597c98\" link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" >\n";
	
	// affichage du titre
	echo "<center>\n";
	echo "<br><H1><img src=\"../img/tux_config_32x32.png\" width=\"32\" height=\"32\" border=\"0\" title=\"Installation de php_conges\" alt=\"Installation de php_conges\"> Mise à jour de l'application PHP_CONGES</H1>\n";
	echo "<br><br>\n";
	
	lance_maj($version, $etape, $DEBUG);
	
	echo "<br><br>";
	echo "<center>\n";
	
	echo "</body>\n</html>\n";


/*****************************************************************************/
/*   FONCTIONS   */


// lance les differente maj depuis la $installed_version jusqu'à la version actuelle
// la $installed_version est préalablement déterminée par get_installed_version() ou renseignée par l'utilisateur
function lance_maj($installed_version, $etape, $DEBUG=FALSE)
{
	if($DEBUG==TRUE) { echo " etape = $etape :: version = $installed_version<br>\n";}

	$PHP_SELF=$_SERVER['PHP_SELF'];
	include("../config.php") ;
	
	$mysql_link = mysql_connexion($mysql_serveur, $mysql_user, $mysql_pass, $mysql_database);
	
	if($etape==0)
	{
		//avant tout , on conseille une sauvegarde de la database !! (cf vieux index.php)
		echo "<h3>vous êtes sur le point de passer de la version <font color=\"black\">$installed_version</font> à la version <font color=\"black\">$config_php_conges_version</font> .</h3>\n";
		echo "<h3>Avant de continuer, prenez soin de faire une sauvegarde de votre base de données !!!</h3>\n";
		echo "<h2>....</h2>\n";
		echo "<br>\n";
		echo "<form action=\"$PHP_SELF\">\n";
		echo "<input type=\"hidden\" name=\"etape\" value=\"1\">\n";
		echo "<input type=\"hidden\" name=\"version\" value=\"$installed_version\">\n";
		echo "<input type=\"submit\" value=\"Continuer\">\n";
		echo "</form>\n";
		echo "<br><br>\n";
		
	}
	elseif($etape==1)
	{
		//verif si create / alter table possible !!!
		if(test_create_table($mysql_link, $DEBUG) == FALSE)
		{
			echo "<font color=\"red\"><b>CREATE TABLE</b> impossible sur la database <b>$mysql_database</b> (verifier les droits mysql de <b>$mysql_user</b>)...</font><br> \n";
			echo "<br>puis ...<br>\n";
			echo "<form action=\"$PHP_SELF\">\n";
			echo "<input type=\"hidden\" name=\"etape\"value=\"1\" >\n";
			echo "<input type=\"hidden\" name=\"version\" value=\"$installed_version\">\n";
			echo "<input type=\"submit\" value=\"recommencer\">\n";
			echo "</form>\n";
		}
		elseif(test_alter_table($mysql_link, $DEBUG) == FALSE)
		{
			echo "<font color=\"red\"><b>ALTER TABLE</b> impossible sur la database <b>$mysql_database</b> (verifier les droits mysql de <b>$mysql_user</b>)...</font><br> \n";
			echo "<br>puis ...<br>\n";
			echo "<form action=\"$PHP_SELF\">\n";
			echo "<input type=\"hidden\" name=\"etape\"value=\"1\" >\n";
			echo "<input type=\"hidden\" name=\"version\" value=\"$installed_version\">\n";
			echo "<input type=\"submit\" value=\"recommencer\">\n";
			echo "</form>\n";
		}
		elseif(test_drop_table($mysql_link, $DEBUG) == FALSE)
		{
			echo "<font color=\"red\"><b>DROP TABLE</b> impossible sur la database <b>$mysql_database</b> (verifier les droits mysql de <b>$mysql_user</b>)...</font><br> \n";
			echo "<br>puis ...<br>\n";
			echo "<form action=\"$PHP_SELF\">\n";
			echo "<input type=\"hidden\" name=\"etape\"value=\"1\" >\n";
			echo "<input type=\"hidden\" name=\"version\" value=\"$installed_version\">\n";
			echo "<input type=\"submit\" value=\"recommencer\">\n";
			echo "</form>\n";
		}
		else
		{
			if($DEBUG==FALSE)
				echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$PHP_SELF?etape=2&version=$installed_version\">";
			else
				echo "<a href=\"$PHP_SELF?etape=2&version=$installed_version\">etape 1  OK</a><br>\n";
		}		
	}
	elseif($etape==2)
	{
		// si on part d'une version <= v1.0
		if( (substr($installed_version, 0, 1)=="0") || ($installed_version=="1.0") )
		{
			//verif si la copie de l'ancien fichier de config est présent et lisible (install/config_old.php)
			if(test_old_config_file($DEBUG) == FALSE)
			{
				echo "<font color=\"red\">\n";
				echo "Le fichier \"<b>install / config_old.php</b>\" n'est pas accessible !<br>\n";
				echo "Afin d'assurer la conservation de votre configuration,<br>\n";
				echo "veuillez copier votre ancien fichier config.php dans le nouveau répertoire \"<b>install</b>\" sous le nom \"<b>config_old.php</b>\" et<br>\n";
				echo "verifier les droits de lecture sur ce fichier. <br>\n";
				echo "</font><br> \n";
				echo "<br>puis ...<br>\n";
				echo "<form action=\"$PHP_SELF\">\n";
				echo "<input type=\"hidden\" name=\"etape\"value=\"2\" >\n";
				echo "<input type=\"hidden\" name=\"version\" value=\"$installed_version\">\n";
				echo "<input type=\"submit\" value=\"continuer\">\n";
				echo "</form>\n";
			}
			else
			{
				if($DEBUG==FALSE)
					echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$PHP_SELF?etape=3&version=$installed_version\">";
				else
					echo "<a href=\"$PHP_SELF?etape=3&version=$installed_version\">etape 2  OK</a><br>\n";
			}
		}
		else
		{
			if($DEBUG==FALSE)
				echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$PHP_SELF?etape=3&version=$installed_version\">";
			else
				echo "<a href=\"$PHP_SELF?etape=3&version=$installed_version\">etape 2  OK</a><br>\n";
		}
		
	}
	elseif($etape==3)
	{
		// ATTENTION on ne passe cette étape que si on est en version inferieure à 1.0 ! (donc en v0.xxx)
		if(substr($installed_version, 0, 1)!="0")
		{
			if($DEBUG==FALSE)
				echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$PHP_SELF?etape=4&version=$installed_version\">";
			else
				echo "<a href=\"$PHP_SELF?etape=4&version=$installed_version\">etape 3  OK</a><br>\n";
		}
		else
		{
			//on lance l'execution de fichier sql de migration l'un après l'autre jusqu a la version O.10.1 ..
			$db_version=explode(".", $installed_version);
			$db_sub_version = (int) $db_version[1];
			 
			for($i=$db_sub_version ; $i <= 10 ; $i++)
			{
				if($i==10) // si on en est à v0.10 on passe en v1.0
					$sql_file = "sql/upgrade_v0.10_to_v1.0.sql";
				else
				{
					$j=$i+1;
					$sql_file = "sql/upgrade_v0.".$i."_to_v0.".$j.".sql";
				}
				if($DEBUG==TRUE) 
					echo "sql_file = $sql_file<br>\n";
				execute_sql_file($sql_file, $mysql_link, $DEBUG);
			}
			if($DEBUG==FALSE)
				echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$PHP_SELF?etape=4&version=1.0\">";
			else
				echo "<a href=\"$PHP_SELF?etape=4&version=1.0\">etape 3  OK</a><br>\n";
		}
		
	}
	elseif($etape==4)
	{
		// on est au moins à la version 1.0 ....
		// ensuite tout se fait en php (plus de script de migration sql)
		
		// on determine la version la + élevée entre $installed_version et 1.0 , et on part de celle là !
		if(substr($installed_version, 0, 1)=="0")
			$start_version="1.0";
		else
			$start_version=$installed_version ;
		
		//on lance l'execution (include) des scripts d'upgrade l'un après l'autre jusqu a la version voulue ($config_php_conges_version) ..
		$db_version=explode(".", $start_version);
		$db_sub_version = (int) $db_version[1];
		$db_stop_version=explode(".", $config_php_conges_version);
		$db_stop_sub_version = (int) $db_stop_version[1];
			 
		for($i=$db_sub_version ; $i < $db_stop_sub_version ; $i++)
		{
			// appel de la fonction appropriée (le nom de fonction est variable ... dépend de la version a upgrader))
			// les focntions sont stockées dans le fichier fonctions_upgrade.php
			$file_upgrade="upgrade_from_v1.$i.php";
			if($DEBUG==TRUE)
				echo "file_upgrade = $file_upgrade<br>\n";
			// execute le script php d'upgrade d'une version (vers la suivante)
			echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$file_upgrade?version=$installed_version\">";
		}
		
/*		if($DEBUG==FALSE)
			echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$PHP_SELF?etape=5&version=$installed_version\">";
		else
			echo "<a href=\"$PHP_SELF?etape=5&version=$installed_version\">etape 4  OK</a><br>\n";
*/		
	}
	elseif($etape==5)
	{
		// FIN : mise à jour de la "installed_version" dans la table conges_config
		$sql_update="UPDATE conges_config SET conf_valeur = '$config_php_conges_version' WHERE conf_nom='installed_version' ";
		$result_update = mysql_query($sql_update, $mysql_link) or die (mysql_error());
		
		// on propose la page de config ....
		echo "<br><br><h2>Installation effectuée avec succès !</h2><br>\n";
		
		$_SESSION['config']=init_config_tab();      // on initialise le tableau des variables de config
		
		echo "<h2>Vous pouvez maintenant :</h2>\n";
		echo "<h3>\n";
		echo "<table border=\"0\">\n";
		echo "<tr><td>-> <a href=\"configure.php\">configurer l'application</a></td></tr>\n";
		echo "<tr><td>-> <a href=\"config_type_absence.php\">configurer les types de congés à gérer</a></td></tr>";
		echo "<tr><td>-> <a href=\"".$_SESSION['config']['URL_ACCUEIL_CONGES']."\">accéder à l'application</a></td></tr>";
		echo "</table>\n";
		echo "</h3><br><br>\n";
	}
	else
	{
		// rien, on ne devrait jammais arriver dans ce else !!!
	}
	
	mysql_close($mysql_link);
}


?>
