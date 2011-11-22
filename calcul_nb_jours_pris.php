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

include("config.php") ;
//include("config_ldap.php");
include("fonctions_conges.php") ;
include("INCLUDE.PHP/fonction.php");
//include("../INCLUDE.PHP/session.php");
//if($config_verif_droits==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}


	/*** initialisation des variables ***/
	$user="";
	$date_debut="";
	$date_fin="";
	/************************************/

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['user'])) { $user=$_GET['user']; }
	if(isset($_GET['date_debut'])) { $date_debut=$_GET['date_debut']; }
	if(isset($_GET['date_fin'])) { $date_fin=$_GET['date_fin']; }
	if(isset($_GET['opt_debut'])) { $opt_debut=$_GET['opt_debut']; }
	if(isset($_GET['opt_fin'])) { $opt_fin=$_GET['opt_fin']; }
	// POST
	/*************************************/


	
	if( ($user!="") && ($date_debut!="") && ($date_fin!="") && ($opt_debut!="") && ($opt_fin!="") )
		affichage($user, $date_debut, $date_fin, $opt_debut, $opt_fin);
	else
		/* APPEL D'UNE AUTRE PAGE immediat */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=user_index.php?session=$session&onglet=nouvelle_absence\">";
		//echo " $user, $date_debut, $date_fin, $opt_debut, $opt_fin <br>\n";




/**********  FONCTIONS  ****************************************/

function affichage($user, $date_debut, $date_fin, $opt_debut, $opt_fin)
{
	global $PHP_SELF;
	//global $session;
	
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : </title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>$user</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	// calcul :
	$nb_jours=compter($user, $date_debut, $date_fin, $opt_debut, $opt_fin);
	echo "<td align=\"center\"><h2>Nombre de jours à prendre : <b>$nb_jours</b></h2></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\"><i>reportez ce nombre dans la case \"NB_Jours_Pris\" du formulaire.</i></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
	echo "	&nbsp;\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
	echo "	<input type=\"button\" value=\"Fermer\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}


// calcule le nb de jours de conges à prendre pour un user entre 2 dates
// retourne le nb de jours  (opt_debut et opt_fin ont les valeurs "am" ou "pm"
function compter($user, $date_debut, $date_fin, $opt_debut, $opt_fin)
{
	global $config_samedi_travail, $config_dimanche_travail;
	
	$nb_jours=0;
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	// on initialise le tableau global des jours fériés s'il ne l'est pas déjà :
	if(!isset($GLOBALS["tab_j_feries"]))
	{
		init_tab_jours_feries($mysql_link);
		//print_r($GLOBALS["tab_j_feries"]);   // verif DEBUG
	}
	
	$current_day=$date_debut;
	$date_limite=jour_suivant($date_fin);
	
	// on va avancer jour par jour et voir s'il est travaillé, férié, rtt, etc ...
	while($current_day!=$date_limite)
	{
		$pieces = explode("-", $current_day);  // date de la forme yyyy-mm-jj
		$y=$pieces[0]; 
		$m=$pieces[1]; 
		$j=$pieces[2]; 
		$timestamp_du_jour=mktime (0,0,0,$m,$j,$y);
		
		// on regarde si le jour est travaillé ou pas
		$j_name=date("D", $timestamp_du_jour);
		if( (($j_name=="Sat")&&($config_samedi_travail==FALSE)) || (($j_name=="Sun")&&($config_dimanche_travail==FALSE)))
		{
			// on ne compte pas ce jour
		}
		else
		{
			if(est_chome($timestamp_du_jour)==TRUE)
			{
				// on ne compte pas ce jour
			}
			else
			{
				// verif des rtt ou temp partiel (dans la table rtt)
				$val_matin="N";
				$val_aprem="N";
				recup_infos_artt_du_jour($user, $timestamp_du_jour, $val_matin, $val_aprem, $mysql_link);
				
				// si on est le premier jour et que les conges commencent à midi
				if( ($current_day==$date_debut) && ($opt_debut=="pm") ) 
				{
					//on ne traite pas le matin
				}
				else
				{
					if($val_matin!="Y")  // pas de rtt le matin
						$nb_jours=$nb_jours+0.5;
				}
				
				// si on est le dernier jour et que les conges finissent à midi
				if( ($current_day==$date_fin) && ($opt_fin=="am") )
				{
					//on ne traite pas l'apres midi
				}
				else
				{
					if($val_aprem!="Y") // pas de rtt l'après midi
						$nb_jours=$nb_jours+0.5;
				}
				
			}
		}
		$current_day=jour_suivant($current_day);
	}
	
	mysql_close($mysql_link);
	
	return $nb_jours; 
}

// renvoit le jour suivant de la date paséée en paramètre sous la forme yyyy-mm-jj
function jour_suivant($date)
{
	$pieces = explode("-", $date);  // date de la forme yyyy-mm-jj
	$y=$pieces[0]; 
	$m=$pieces[1]; 
	$j=$pieces[2]; 

	$lendemain = date("Y-m-d", mktime(0, 0, 0, $m , $j+1, $y) );
	return $lendemain;
}

?>
