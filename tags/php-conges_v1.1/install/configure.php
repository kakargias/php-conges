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

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : "") ) ;

// REMARQUE :
// Dans cette page, on gere la possibilit� de ne pas �tre dans une session (la page peut �tre appel�e directement, 
// pour la premi�re configuration de l'appli)

include("../config_ldap.php");
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");

if(!isset($_SESSION['config']))
{
	$_SESSION['config']=init_config_tab();      // on initialise le tableau des variables de config
}

if($session!="")
	include("../INCLUDE.PHP/session.php");
	

$verif_droits_file="INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}



	
	/*** initialisation des variables ***/
	$action="";
	$tab_new_values=array();
	/************************************/

	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['action'])) {$action = $_GET['action'];}
	// POST
	if(isset($_POST['action'])) { $action=$_POST['action']; }
	if(isset($_POST['tab_new_values'])) { $tab_new_values=$_POST['tab_new_values']; }

	/*************************************/
	
	//DEBUG
	//print_r($tab_new_values); echo "<br>\n";

	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	if($action=="commit")
		commit_saisie($tab_new_values, $mysql_link, $session);
	else
		affichage($mysql_link, $session);
	
	mysql_close($mysql_link);


	
/**************************************************************************************/
/**********  FONCTIONS  ***************************************************************/


function affichage($mysql_link, $session)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
    
	// Affichage du panneau principal
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<TITLE> CONGES : Configuration </TITLE>\n";
	echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";

	/**************************************/
	// affichage du titre
	echo "<br><center><H1><img src=\"../img/tux_config_32x32.png\" width=\"32\" height=\"32\" border=\"0\" title=\"Configuration de php_conges\" alt=\"Configuration de php_conges\"> Configuration de l'Application PHP_CONGES</H1></center>\n";
	echo "<br>\n";
	/**************************************/
	// affichage de la liste des variables
	
	if($session=="")
		echo "<form action=\"$PHP_SELF\" method=\"POST\"> \n";
	else
		echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
	echo "<input type=\"hidden\" name=\"action\" value=\"commit\">\n";
	
	//requ�te qui r�cup�re les informations de config
	$sql1 = "SELECT * FROM conges_config ORDER BY conf_groupe ASC";
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : admin_config.php : <br>\n".mysql_error());
	
	$old_groupe="";
	while ($data = mysql_fetch_array($ReqLog1)) 
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
				echo "<input type=\"submit\"  value=\"Enregistrer les modifications\"><br>";
				echo "</td></tr>\n";
				echo "</table>\n";
			}
			echo "<br>\n";
			echo "<table width=\"100%\">\n";
			echo "<tr><td>\n";
			echo "    <fieldset class=\"cal_saisie\">\n";
			echo "    <legend class=\"boxlogin\">$conf_groupe</legend>\n";
			$old_groupe = $conf_groupe ;
		}
		
		// affichage commentaire 
		echo "<br><i>$conf_commentaire</i><br>\n";
		
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
	echo "</td></tr>\n";
	echo "<tr><td align=\"right\">\n";
	echo "<input type=\"submit\"  value=\"Enregistrer les modifications\"><br>";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
  
	echo "<br><br>\n";
	echo "<form>\n";
	echo "<center><input type=\"button\" value=\"Fermer cette Fen�tre\" onClick=\"javascript:window.close();\"></center>\n";
	echo "</form>\n";
	echo "<br><br>\n";
	
	echo "</body>";
	echo "</html>";
}


function commit_saisie(&$tab_new_values, $mysql_link, $session)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
    
	// verif si des champs sont vides 
	if ( isset($nom) && isset($new_valeur) && empty($new_valeur) )
	{
		echo "<span class = \"messages\">Erreur, champs non remplis !</span><br>";
		if($session=="")
			echo "<META HTTP-EQUIV=REFRESH CONTENT=\"1; URL=$PHP_SELF\">";
		else
			echo "<META HTTP-EQUIV=REFRESH CONTENT=\"1; URL=$PHP_SELF?session=$session\">";
	}

	foreach($tab_new_values as $key => $value )
	{
		$sql2 = "UPDATE conges_config SET conf_valeur = '$value' WHERE conf_nom = '$key'";
		$ReqLog2 = mysql_query($sql2, $mysql_link) or die("ERREUR : fonction commit_saisie() :<br>\n $sql2<br>\n".mysql_error());
	}
	
	$_SESSION['config']=init_config_tab();      // on re-initialise le tableau des variables de config
	
	echo "<span class = \"messages\">Modifications enregistr�es avec succ�s !</span><br>";
	if($session=="")
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?\">";
	else
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session\">";
    

}


?>