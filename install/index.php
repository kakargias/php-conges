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

//include ROOT_PATH .'fonctions_conges.php' ;
session_start();
$_SESSION['lang'] = 'fr_FR';

include INCLUDE_PATH .'fonction.php';

include'fonctions_install.php' ;
include ROOT_PATH .'fonctions_conges.php' ;

$PHP_SELF=$_SERVER['PHP_SELF'];

$DEBUG=FALSE;
//$DEBUG=TRUE;

$session=session_id();

// verif des droits du user à afficher la page
//verif_droits_user($session, "is_admin", $DEBUG);

//recup de la langue
$lang=(isset($_GET['lang']) ? $_GET['lang'] : ((isset($_POST['lang'])) ? $_POST['lang'] : "") ) ;


	if($lang=="")
	{
		header_popup();
		echo "<br><br>\n";
		echo "Choisissez votre langue :<br> \n";
		echo "Choose your language :<br>\n";
			echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
			// affichage de la liste des langues supportées ...
			// on lit le contenu du répertoire lang et on parse les nom de ficher (ex lang_fr_francais.php)
			affiche_select_from_lang_directory("", "");

			echo "<br>\n";
			echo "<input type=\"submit\" value=\"OK\">\n";
			echo "</form>\n";
		bottom();
	}
	elseif(test_dbconnect_file($DEBUG)!=TRUE)
	{
		$_SESSION['langue']=$lang;      // sert ensuite pour mettre la langue dans la table config
//		$tab_lang_file = glob("lang/lang_".$lang.'_*.php');
//		include$tab_lang_file[0] ;
//		include$lang_file ;

		header_popup();
		echo "<center>\n";
		echo "<br><br>\n";
		echo  _('install_le_fichier') ." <b>\"dbconnect.php\"</b> ". _('install_bad_fichier') .".<br> \n";
		echo  _('install_read_the_file') ." INSTALL.txt<br>\n";
		echo "<br><a href=\"$PHP_SELF?session=$session\">". _('install_reload_page') ." ....</a><br>\n";
		bottom();
	}
	else
	{
		include CONFIG_PATH .'dbconnect.php';
		include ROOT_PATH .'version.php';

		if( !test_database() )
		{
			header_popup();
			echo "<center>\n";
			echo "<br><br>\n";
			echo "<b>". _('install_db_inaccessible') ." ... <br><br>\n";
			echo  _('install_verifiez_param_file') ." dbconnect.php .<br>\n";
			echo "(". _('install_verifiez_priv_mysql') .")<br><br>\n";
			echo "<i>". _('install_read_the_file') ." INSTALL.txt</i><br>\n";
			echo "<br><a href=\"$PHP_SELF?session=$session\">". _('install_reload_page') ." ....</a><br>\n";
			echo "</b>\n";
			bottom();
		}
		else
		{
			$installed_version = get_installed_version( $DEBUG);

			if($installed_version==0)   // num de version inconnu
			{
				install($lang,  $DEBUG);
			}
			else
			{
				// on compare la version déclarée dans la database avec la version déclarée dans le fichier de config
				if($installed_version != $config_php_conges_version)
				{
					// on attaque une mise a jour à partir de la version installée
					echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=mise_a_jour.php?version=$installed_version&lang=$lang\">";
				}
				else
				{
					// pas de mise a jour a faire : on propose les pages de config
					echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=../config/\">";
				}
			}

			
		}
	}



/*****************************************************************************/
/*   FONCTIONS   */

// cette fonction verif si une version à déja été installée ou non....
// elle lance une creation/initialisation de la base
// ou une migration des version antérieures ....
function install($lang,  $DEBUG=FALSE)
{
	// soit, c'est une install complète , soit c'est une mise à jour d'une version non déterminée
	
	header_popup('PHP_CONGES : Installation');

	// affichage du titre
	echo "<center>\n";
	echo "<br><H1><img src=\"". TEMPLATE_PATH ."img/tux_config_32x32.png\" width=\"32\" height=\"32\" border=\"0\" title=\"". _('install_install_phpconges') ."\" alt=\"". _('install_install_phpconges') ."\"> ". _('install_index_titre') ."</H1>\n";
	echo "<br><br>\n";

	echo "<table border=\"0\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td colspan=\"3\"><h2>". _('install_no_prev_version_found') .".<br>". _('install_indiquez') ." ...</h2><br><br></td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\">\n";
	echo "<td valign=top>\n";
	echo "\n";
	echo "<h3>... ". _('install_nouvelle_install') ."</h3>\n";
	echo "<br>\n";

	// Formulaire : lance install.php
	echo "<form action=\"install.php\" method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"lang\" value=\"$lang\">\n";
	echo "<input type=\"submit\" value=\"". _('form_start') ."\">\n";
	echo "</form>\n";
	echo "</td>\n";
	echo "<td><img src=\"". TEMPLATE_PATH ."img/shim.gif\" width=\"100\" height=\"10\" border=\"0\" vspace=\"0\" hspace=\"0\"></td>\n";
	echo "<td valign=top>\n";
	echo "<h3>... ". _('install_mise_a_jour') ."</h3><b>". _('install_indiquez_pre_version') ." :</b><br><br>\n";

	// Formulaire : lance mise_a_jour.php
	echo "<form action=\"mise_a_jour.php\" method=\"POST\">\n";
	// affichage de la liste des versions ...
	echo "<select name=\"version\">\n";
	echo "<option value=\"0\">". _('install_installed_version') ."</option>\n";
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
	echo "<input type=\"hidden\" name=\"lang\" value=\"$lang\">\n";
	echo "<input type=\"submit\" value=\"". _('form_start') ."\">\n";
	echo "</form>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	bottom();
}


