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
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : "") ) ;

include("../config_ldap.php");
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
if(!isset($_SESSION['config']))
	$_SESSION['config']=init_config_tab();      // on initialise le tableau des variables de config
include("../INCLUDE.PHP/session.php");
//include("fonctions_install.php") ;


$DEBUG = FALSE ;
//$DEBUG = TRUE ;

// verif des droits du user à afficher la page
verif_droits_user($session, "is_admin", $DEBUG);

if($DEBUG==TRUE) { echo "SESSION = "; print_r($_SESSION); echo "<br>\n";}


	/*** initialisation des variables ***/
	$action="";
	$tab_new_values=array();
	/************************************/

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['action'])) {$action = $_GET['action'];}
	// POST
	if(isset($_POST['action'])) { $action=$_POST['action']; }
	if(isset($_POST['tab_new_values'])) { $tab_new_values=$_POST['tab_new_values']; }

	/*************************************/

	if($DEBUG==TRUE) { echo "tab_new_values = "; print_r($tab_new_values); echo "<br>\n"; }


	if($action=="commit")
		commit_saisie($tab_new_values, $session, $DEBUG);
	else
		affichage($session, $DEBUG);


/**************************************************************************************/
/**********  FONCTIONS  ***************************************************************/


function affichage($session, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	// Affichage du panneau principal
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<TITLE> CONGES : Configuration </TITLE>\n";
	echo "</head>\n";

	//$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	//echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" >\n";

	/**************************************/
	// affichage du titre
	echo "<br><center><H1><img src=\"../img/tux_config_32x32.png\" width=\"32\" height=\"32\" border=\"0\" title=\"".$_SESSION['lang']['config_appli_titre_2']."\" alt=\"".$_SESSION['lang']['config_appli_titre_2']."\"> ".$_SESSION['lang']['config_appli_titre_1']."</H1></center>\n";
	echo "<br>\n";
	/**************************************/

	affiche_bouton_retour($session);


	// affichage de la liste des variables

	if($session=="")
		echo "<form action=\"$PHP_SELF\" method=\"POST\"> \n";
	else
		echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
	echo "<input type=\"hidden\" name=\"action\" value=\"commit\">\n";

	//requête qui récupère les informations de config
	$sql1 = "SELECT * FROM conges_config ORDER BY conf_groupe ASC";
	$ReqLog1 = requete_mysql($sql1, "affichage", $DEBUG);

	$old_groupe="";
	while ($data =$ReqLog1->fetch_array())
	{
		$conf_nom = $data['conf_nom'];
		$conf_valeur = $data['conf_valeur'];
		$conf_groupe = $data['conf_groupe'];
		$conf_type = $data['conf_type'];
		$conf_commentaire = $data['conf_commentaire'];

		// changement de groupe de variables
		if($old_groupe != $conf_groupe)
		{
			if($old_groupe!="")
			{
				echo "</td></tr>\n";
				echo "<tr><td align=\"right\">\n";
				echo "<input type=\"submit\"  value=\"".$_SESSION['lang']['form_save_modif']."\"><br>";
				echo "</td></tr>\n";
				echo "</table>\n";
			}
			echo "<br>\n";
			echo "<table width=\"100%\">\n";
			echo "<tr><td>\n";
			echo "    <fieldset class=\"cal_saisie\">\n";
			echo "    <legend class=\"boxlogin\">".$_SESSION['lang'][$conf_groupe]."</legend>\n";
			$old_groupe = $conf_groupe ;
		}

		// si on est sur le parametre "lang" on liste les fichiers de langue du répertoire install/lang
		if($conf_nom=="lang")
		{
			echo "Choisissez votre langue :<br> \n";
			echo "Choose your language :<br>\n";
			// affichage de la liste des langues supportées ...
			// on lit le contenu du répertoire lang et on parse les nom de ficher (ex lang_fr_francais.php)
			affiche_select_from_lang_directory("tab_new_values[$conf_nom]");
		}
		else
		{
			// affichage commentaire
			echo "<br><i>".$_SESSION['lang'][$conf_commentaire]."</i><br>\n";

			// affichage saisie variable
			if($conf_nom=="installed_version")
			{
				echo "<b>$conf_nom&nbsp;&nbsp;=&nbsp;&nbsp;$conf_valeur</b><br>";
			}
			elseif( ($conf_type=="texte") || ($conf_type=="path") )
			{
				echo "<b>$conf_nom</b>&nbsp;=&nbsp;<input type=\"text\" size=\"50\" maxlength=\"200\" name=\"tab_new_values[$conf_nom]\" value=\"$conf_valeur\"><br>";
			}
			elseif($conf_type=="boolean")
			{
				echo "<b>$conf_nom</b>&nbsp;=&nbsp;<select name=\"tab_new_values[$conf_nom]\">";
				echo "<option value=\"TRUE\"";
				if($conf_valeur=="TRUE") echo "selected";
				echo ">TRUE</option>";
				echo "<option value=\"FALSE\"";
				if($conf_valeur=="FALSE") echo "selected";
				echo ">FALSE</option>";
				echo "</select><br>";
			}
			elseif(substr($conf_type,0,4)=="enum")
			{
				echo "<b>$conf_nom</b>&nbsp;=&nbsp;<select name=\"tab_new_values[$conf_nom]\">";
				$options=explode("/", substr(strstr($conf_type, '='),1));
				for($i=0; $i<count($options); $i++)
				{
					echo "<option value=\"".$options[$i]."\"";
					if($conf_valeur==$options[$i]) echo "selected";
					echo ">".$options[$i]."</option>";
				}
				echo "</select><br>";
			}
			echo "<br>";
		}

	}
	echo "</td></tr>\n";
	echo "<tr><td align=\"right\">\n";
	echo "<input type=\"submit\"  value=\"".$_SESSION['lang']['form_save_modif']."\"><br>";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "<br><br>\n";
	affiche_bouton_retour($session);
	echo "<br><br>\n";


	echo "</body>";
	echo "</html>";
}


function commit_saisie(&$tab_new_values, $session, $DEBUG=FALSE)
{
$sql=SQL::singleton();
//$DEBUG=TRUE;
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$timeout=2 ;  // temps d'attente pour rafraichir l'écran après l'update !

	if($DEBUG==TRUE) { echo "SESSION = "; print_r($_SESSION); echo "<br>\n"; }

	foreach($tab_new_values as $key => $value )
	{
		// CONTROLE gestion_conges_exceptionnels
		// si désactivation les conges exceptionnels, on verif s'il y a des conges exceptionnels enregistres ! si oui : changement impossible !
		if(($key=="gestion_conges_exceptionnels") && ($value=="FALSE") )
		{
			$sql_abs="SELECT ta_id, ta_libelle FROM conges_type_absence WHERE ta_type='conges_exceptionnels' ";
			$ReqLog_abs = requete_mysql($sql_abs, "commit_saisie", $DEBUG);

			if($ReqLog_abs->num_rows !=0)
			{
				echo "<b>".$_SESSION['lang']['config_abs_desactive_cong_excep_impossible']."</b><br>\n";
				$value = "TRUE" ;
				$timeout=5 ;
			}
		}
		
		// CONTROLE jour_mois_limite_reliquats
		// si modif de jour_mois_limite_reliquats, on verifie le format ( 0 ou jj-mm) , sinon : changement impossible !
		if( ($key=="jour_mois_limite_reliquats") && ($value!= "0") )
		{
			$t=explode("-", $value);
			if(checkdate($t[1], $t[0], date("Y"))==FALSE)
			{		
				echo "<b>".$_SESSION['lang']['config_jour_mois_limite_reliquats_modif_impossible']."</b><br>\n";
				$sql_date="SELECT conf_valeur FROM conges_config WHERE conf_nom='jour_mois_limite_reliquats' ";
				$ReqLog_date = requete_mysql($sql_date, "commit_saisie", $DEBUG);
				$data = $ReqLog_date->fetch_row();
				$value = $data[0] ;
				$timeout=5 ;
			}
		}
		
		// Mise à jour
		$sql2 = 'UPDATE conges_config SET conf_valeur = \''.$value.'\' WHERE conf_nom =\''.$sql->escape($key).'\' ';
		$ReqLog2 = requete_mysql($sql2, "commit_saisie", $DEBUG);
	}

	$_SESSION['config']=init_config_tab();      // on re-initialise le tableau des variables de config

	// enregistrement dans les logs
	$comment_log = "nouvelle configuration de php_conges ";
	log_action(0, "", "", $comment_log, $DEBUG);

	echo "<span class = \"messages\">".$_SESSION['lang']['form_modif_ok']."</span><br>";
	if($session=="")
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"$timeout; URL=$PHP_SELF?\">";
	else
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"$timeout; URL=$PHP_SELF?session=$session\">";


}



?>
