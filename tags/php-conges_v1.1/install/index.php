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
	
	if(test_config_file($DEBUG)!=TRUE)
	{
		echo "<html>\n<body>\n";
		echo "<center>\n";
		echo "<br><br>\n";
		echo "Le fichier <b>\"config.php\"</b> est introuvable dans le répertoire racine du nouveau php_conges, ou n'a pas des droits en lecture suffisant.<br> \n";
		echo "reportez vous au fichier INSTALL.txt<br>\n";
		echo "<br><a href=\"$PHP_SELF\">puis rechargez cette page ....</a><br>\n";
		echo "</center>\n";
		echo "</body>\n</html>\n";
	}
	else
	{
		include '../config.php';
		if(test_database($DEBUG)!=TRUE)
		{
			echo "<html>\n<body>\n";
			echo "<center>\n";
			echo "<br><br>\n";
			echo "<b>la database n'est pas accessible ... <br><br>\n";
			echo "Veuillez vérifier les paramètres du fichier config.php .<br>\n";
			echo "(Assurez vous que la database, l'utilisateur et les privilèges MySql ont bien été créés.)<br><br>\n";
			echo "<i>reportez vous au fichier INSTALL.txt</i><br>\n";
			echo "<br><a href=\"$PHP_SELF\">puis rechargez cette page ....</a><br>\n";
			echo "</b></center>\n";
			echo "</body>\n</html>\n";
		}
		else
		{
			$mysql_link = mysql_connexion($mysql_serveur, $mysql_user, $mysql_pass, $mysql_database);

			$installed_version = get_installed_version($mysql_link, $DEBUG);
			if($installed_version==0)   // num de version inconnu
			{
				install($mysql_link, $DEBUG);
			}
			else
			{
				// on compare la version déclarée dans la database avec la version déclarée dans le fichier de config
				if($installed_version != $config_php_conges_version)
				{
					// on attaque une mise a jour à partir de la version installée
					echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=mise_a_jour.php?version=$installed_version\">";
				}
				else
				{
					// pas de mise a jour a faire : on propose les pages de config
					propose_config($DEBUG);
				}
			}

			mysql_close($mysql_link);
		}
	}
			


/*****************************************************************************/
/*   FONCTIONS   */

// cette fonction verif si une version à déja été installée ou non....
// elle lance une creation/initialisation de la base 
// ou une migration des version antérieures ....
function install($mysql_link, $DEBUG=FALSE)
{
	// soit, c'est une install complète , soit c'est une mise à jour d'une version non déterminée
	
	echo "<html>\n<head>\n";
	echo "<TITLE> PHP_CONGES : Installation : </TITLE>\n</head>\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
				
	echo "<body text=\"#000000\" bgcolor=\"#597c98\" link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" >\n";
			
	// affichage du titre
	echo "<center>\n";
	echo "<br><H1><img src=\"../img/tux_config_32x32.png\" width=\"32\" height=\"32\" border=\"0\" title=\"Installation de php_conges\" alt=\"Installation de php_conges\"> Application PHP_CONGES</H1>\n";
	echo "<br><br>\n";

	echo "<table border=\"0\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td colspan=\"3\"><h2>Aucune version antèrieure n'a  pu être détermineé.<br>" .
			"Veuillez indiquer  s'il s'agit ...</h2><br><br></td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\">\n";
	echo "<td valign=top>\n";
	echo "\n";
	echo "<h3>... d'une Nouvelle Installation</h3>\n";
	echo "<br>\n";
	echo "<form action=\"install.php\">\n";
	echo "<input type=\"submit\" value=\"Commencer\">\n";
	echo "</form>\n";
	echo "</td>\n";
	echo "<td><img src=\"../img/shim.gif\" width=\"100\" height=\"10\" border=\"0\" vspace=\"0\" hspace=\"0\"></td>\n";
	echo "<td valign=top>\n";
	echo "<h3>... d'une Mise à Jour</h3><b>veuillez indiquer la version déjà installée :</b><br><br>\n";
	echo "<form action=\"mise_a_jour.php\">\n";
	// affichage de la liste des versions ...
	echo "<select name=\"version\">\n";
	echo "<option value=\"0\">version déjà installée</option>\n";
	echo "<option value=\"1.0\">v1.0.x</option>\n";
	echo "<option value=\"0.10\">v0.10.x</option>\n";
	echo "<option value=\"0.9\">v0.9.x</option>\n";
	echo "<option value=\"0.8\">v0.8.x</option>\n";
	echo "<option value=\"0.7\">v0.7.x</option>\n";
	echo "<option value=\"0.6\">v0.6.x</option>\n";
	echo "<option value=\"0.5\">v0.5.x</option>\n";
	echo "<option value=\"0.4\">v0.4</option>\n";
	echo "</select>\n";
	echo "<br>\n";
	echo "<input type=\"submit\" value=\"Commencer\">\n";
	echo "</form>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<center>\n";
				
	echo "</body>\n</html>\n";
}

function propose_config($DEBUG=FALSE)
{
	echo "<html>\n<head>\n";
	echo "<TITLE> PHP_CONGES : Installation : </TITLE>\n</head>\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
				
	echo "<body text=\"#000000\" bgcolor=\"#597c98\" link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" >\n";
			
	// affichage du titre
	echo "<center>\n";
	echo "<br><H1><img src=\"../img/tux_config_32x32.png\" width=\"32\" height=\"32\" border=\"0\" title=\"Installation de php_conges\" alt=\"Installation de php_conges\"> Application PHP_CONGES</H1>\n";
	echo "<br><br>\n";
	
		echo "<h2>Configuration :</h2>\n";
		echo "<h3>\n";
		echo "<table border=\"0\">\n";
		echo "<tr><td>-> <a href=\"configure.php\">configurer l'application</a></td></tr>\n";
		echo "<tr><td>-> <a href=\"config_type_absence.php\">configurer les types de congés à gérer</a></td></tr>";
		echo "</table>\n";
		echo "</h3><br><br>\n";

	echo "<center>\n";
				
	echo "</body>\n</html>\n";
}
?>
