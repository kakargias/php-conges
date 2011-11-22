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

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("fonctions_install.php") ;
	
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$DEBUG=FALSE;
	//$DEBUG=TRUE;
	
	echo "<html>\n<head>\n";
	echo "<TITLE> PHP_CONGES : Installation : </TITLE>\n</head>\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
		
	echo "<body text=\"#000000\" bgcolor=\"#597c98\" link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" >\n";
	
	// affichage du titre
	echo "<center>\n";
	echo "<br><H1><img src=\"../img/tux_config_32x32.png\" width=\"32\" height=\"32\" border=\"0\" title=\"Installation de php_conges\" alt=\"Installation de php_conges\"> Installation de l'application PHP_CONGES</H1>\n";
	echo "<br><br>\n";
		
	lance_install($DEBUG); 
	
	echo "<br><br>";
	echo "<center>\n";
	
	echo "</body>\n</html>\n";


/*****************************************************************************/
/*   FONCTIONS   */

// install la nouvelle version dans une database vide ... et config
function lance_install($DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	include("../config.php") ;
	$mysql_link = mysql_connexion($mysql_serveur, $mysql_user, $mysql_pass, $mysql_database);
	
	//verif si create / alter table possible !!!
	if(test_create_table($mysql_link, $DEBUG) == FALSE)
	{
		echo "<font color=\"red\"><b>CREATE TABLE</b> impossible sur la database <b>$mysql_database</b> (verifier les droits mysql de <b>$mysql_user</b>)...</font><br> \n";
		echo "<br>puis ...<br>\n";
		echo "<form action=\"$PHP_SELF\">\n";
		echo "<input type=\"submit\" value=\"recommencer\">\n";
		echo "</form>\n";
	}
	elseif(test_drop_table($mysql_link, $DEBUG) == FALSE)
	{
		echo "<font color=\"red\"><b>DROP TABLE</b> impossible sur la database <b>$mysql_database</b> (verifier les droits mysql de <b>$mysql_user</b>)...</font><br> \n";
		echo "<br>puis ...<br>\n";
		echo "<form action=\"$PHP_SELF\">\n";
		echo "<input type=\"submit\" value=\"recommencer\">\n";
		echo "</form>\n";
	}
	else
	{
		//on execute le scrip [nouvelle vesion].sql qui cr�e et initialise les tables 
		$result = execute_sql_file("sql/php_conges_v1.1.sql", $mysql_link, $DEBUG);
		
		// FIN : mise � jour de la "installed_version" dans la table conges_config
		$sql_update="UPDATE conges_config SET conf_valeur = '$config_php_conges_version' WHERE conf_nom='installed_version' ";
		$result_update = mysql_query($sql_update, $mysql_link) or die (s.mysql_error());
		
		// on propose la page de config ....
		echo "<br><br><h2>Installation effectu�e avec succ�s !</h2><br>\n";
		
		$_SESSION['config']=init_config_tab();      // on initialise le tableau des variables de config
		
		echo "<h2>Vous pouvez maintenant :</h2>\n";
		echo "<h3>\n";
		echo "<table border=\"0\">\n";
		echo "<tr><td>-> <a href=\"configure.php\">configurer l'application</a></td></tr>\n";
		echo "<tr><td>-> <a href=\"config_type_absence.php\">configurer les types de cong�s � g�rer</a></td></tr>";
		echo "<tr><td>-> <a href=\"".$_SESSION['config']['URL_ACCUEIL_CONGES']."\">acc�der � l'application</a></td></tr>";
		echo "</table>\n";
		echo "</h3><br><br>\n";
	}
}


?>
