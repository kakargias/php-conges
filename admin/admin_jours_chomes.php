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

include("../config.php") ;
include("../config_ldap.php");
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
if($config_verif_droits==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}


	/*** initialisation des variables ***/
	$choix_action="";
	$year_calendrier_saisie=0;
	/************************************/

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['choix_action'])) { $choix_action=$_GET['choix_action']; }
	if(isset($_GET['year_calendrier_saisie'])) { $year_calendrier_saisie=$_GET['year_calendrier_saisie']; }
	// POST
	if( (!isset($choix_action)) || ($choix_action=="") )
		if(isset($_POST['choix_action'])) { $choix_action=$_POST['choix_action']; }
	if(isset($_POST['tab_checkbox_j_chome'])) { $tab_checkbox_j_chome=$_POST['tab_checkbox_j_chome']; }
	/*************************************/

	if($choix_action=="")
		saisie($year_calendrier_saisie);
	elseif($choix_action=="confirm")
		confirm_saisie($tab_checkbox_j_chome);
	elseif($choix_action=="commit")
		commit_saisie($tab_checkbox_j_chome);


/**********  FONCTIONS  ****************************************/

function saisie($year_calendrier_saisie)
{
	global $PHP_SELF;
	global $session;
	
	// si l'année n'est pas renseignée, on prend celle du jour
	if( (!isset($year_calendrier_saisie)) || ($year_calendrier_saisie==0))
		$year_calendrier_saisie=date("Y");
		
	// on construit le tableau de l'année considérée
	$tab_year=array();
	get_tableau_jour_feries($year_calendrier_saisie, $tab_year);
	//print_r($tab_year);
	
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : administration</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>Saisie des jours chômés</h1>\n";

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;

	// table contenant le fieldset
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr align=\"center\">\n";
		echo "<td>\n";
		echo "<fieldset class=\"cal_saisie\">\n";
			// tableau contenant les mois
			echo "<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			// ligne des boutons de défilement
			echo "<tr align=\"center\">\n";
				$year_calendrier_saisie_prec=$year_calendrier_saisie-1;
				$year_calendrier_saisie_suiv=$year_calendrier_saisie+1;
					// recul d'un an
					echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie=$year_calendrier_saisie_prec\"> <img src=\"../img/simfirs.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"année précédente\" title=\"année précédente\"> </a></td>\n";
					echo "<td colspan=\"2\" align=\"center\" class=\"big\">$year_calendrier_saisie</td>\n";
					//echo "<td align=\"center\" class=\"big\">&nbsp;</td>\n";
					// avance d'un an
					echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie=$year_calendrier_saisie_suiv\"> <img src=\"../img/simlast.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"année suivante\" title=\"année suivante\"> </a></td>\n";

			echo "</tr>\n";
			// ligne janvier / fevrier / mars / avril
			echo "<tr align=\"center\" valign=\"top\">\n";
				echo "<td>\n"; // janvier
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "01", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // fevrier
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "02", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // mars
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "03", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // avril
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "04", $tab_year);  
				echo "</td>\n";
			echo "</tr>\n";
			// ligne mai / juin / juillet / aout
			echo "<tr align=\"center\" valign=\"top\">\n";
				echo "<td>\n"; // mai
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "05", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // juin
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "06", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // juillet
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "07", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // aout
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "08", $tab_year);  
				echo "</td>\n";
			echo "</tr>\n";
			// ligne septembre / octobre / novembre / decembre
			echo "<tr align=\"center\" valign=\"top\">\n";
				echo "<td>\n"; // septembre
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "09", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // octobre
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "10", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // novembre
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "11", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // décembre
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "12", $tab_year);  
				echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		echo "</fieldset>\n";
		echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	//table contenant les bountons
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n");
	printf("<tr align=\"center\">\n");
	printf("<td>\n");
		printf("<input type=\"hidden\" name=\"choix_action\" value=\"confirm\">\n");
		printf("<input type=\"submit\" value=\"Valider\">   <input type=\"button\" value=\"Abandonner\" onClick=\"javascript:window.close();\">\n");
	printf("</td>\n");
	printf("</tr>\n");
	printf("</table>\n");
			

	printf("</form>\n" ) ;

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}



// affichage du calendrier du mois avec les case à cocher
// on lui passe en parametre le tableau des jour chomé de l'année (pour pré-cocher certaines cases)
function  affiche_calendrier_saisie_jours_chomes($year, $mois, $tab_year) 
{
	global $config_semaine_bgcolor, $config_week_end_bgcolor;
	global $config_samedi_travail, $config_dimanche_travail ;

	$jour_today=date("j");
	$jour_today_name=date("D");
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
	$mois_name=date_fr("F", $first_jour_mois_timestamp);
	$first_jour_mois_rang=date("w", $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)
		
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"250\" bgcolor=$config_semaine_bgcolor>\n";
	/* affichage  2 premieres lignes */
	echo "	<tr align=\"center\" bgcolor=\"$config_light_grey_bgcolor\"><td colspan=7 class=\"titre\"> $mois_name $year </td></tr>\n" ;
	echo "	<tr bgcolor=\"$config_light_grey_bgcolor\">\n";
	echo "		<td class=\"cal-saisie2\">L</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">J</td><td class=\"cal-saisie2\">V</td><td class=\"cal-saisie2\">S</td><td class=\"cal-saisie2\">D</td>\n";
	echo "	</tr>\n" ;
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) 
	{
		if( (($i==6)&&($config_samedi_travail==FALSE)) || (($i==7)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) 
	{
		$j=$i-$first_jour_mois_rang+1 ;
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne) */
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) 
	{
		if( (($i==35-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==36-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) 
	{
		if( (($i==42-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==43-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}

function confirm_saisie($tab_checkbox_j_chome)
{
	global $PHP_SELF;
	global $session;
		
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : administration</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>Saisie des jours chômés</h1>\n";

	//echo "tab_checkbox_j_chome : <br>\n";
	//print_r($tab_checkbox_j_chome);

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
		foreach($tab_checkbox_j_chome as $key => $value)
		{
			$date_affiche=eng_date_to_fr($key);
			echo "$date_affiche<br>\n";
			printf("<input type=\"hidden\" name=\"tab_checkbox_j_chome[$key]\" value=\"$value\">\n");
		}
		printf("<input type=\"hidden\" name=\"choix_action\" value=\"commit\">\n");
		printf("<input type=\"submit\" value=\"Confirmer cette Saisie\">\n");
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
	echo "	<input type=\"button\" value=\"Annuler\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}

function commit_saisie($tab_checkbox_j_chome)
{
	global $PHP_SELF;
	global $session;
		
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : administration</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>Saisie des jours chômés</h1>\n";

	//echo "tab_checkbox_j_chome : <br>\n";
	//print_r($tab_checkbox_j_chome);
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	// si l'année est déja renseignée dans la database, on efface ttes les dates de l'année
	if(verif_year_deja_saisie($tab_checkbox_j_chome, $mysql_link)==TRUE)
		$result=delete_year($tab_checkbox_j_chome, $mysql_link);
		
	// on insert les nouvelles dates saisies
	$result=insert_year($tab_checkbox_j_chome, $mysql_link);
	
	mysql_close($mysql_link);
	
	if($result==TRUE)
		echo "<br>Données enregistrées avec succès.<br><br>\n";
	else
		echo "<br>ECHEC de l'enregistrement !<br><br>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<td align=\"center\">\n";
	echo "	<input type=\"button\" value=\"Fermer cette Fenêtre\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}


function insert_year($tab_checkbox_j_chome, $mysql_link)
{
	$sql_insert="";
	foreach($tab_checkbox_j_chome as $key => $value)
	{
		$sql_insert="INSERT INTO conges_jours_feries SET jf_date='$key' ;";
		$result = mysql_query($sql_insert, $mysql_link) or die("ERREUR : insert_year() : $sql_insert : <br>\n".mysql_error());
	}

	return TRUE;
}

function delete_year($tab_checkbox_j_chome, $mysql_link)
{
	$date_1=key($tab_checkbox_j_chome);
	$year=substr($date_1, 0, 4);
	//echo "year= $year<br>\n";
	$sql_delete="DELETE FROM conges_jours_feries WHERE jf_date LIKE '$year%' ;";
	$result = mysql_query($sql_delete, $mysql_link) or die("ERREUR : delete_year() : $sql_delete : <br>\n".mysql_error());

	return TRUE;
}

function verif_year_deja_saisie($tab_checkbox_j_chome, $mysql_link)
{
	$date_1=key($tab_checkbox_j_chome);
	$year=substr($date_1, 0, 4);
	//echo "year= $year<br>\n";
	$sql_select="SELECT jf_date FROM conges_jours_feries WHERE jf_date LIKE '$year%' ;";
	$relog = mysql_query($sql_select, $mysql_link) or die("ERREUR : delete_year() : $sql_select : <br>\n".mysql_error());
	
	$count=mysql_num_rows($relog);
	if($count==0)
		return FALSE;
	else
		return TRUE;
}


// retourne un tableau des jours feriés de l'année dans un tables passé par référence
function get_tableau_jour_feries($year, &$tab_year)
{
	//connexion mysql
	$mysql_link = connexion_mysql() ;

	$sql_select="SELECT jf_date FROM conges_jours_feries WHERE jf_date LIKE '$year-%' ";
	$res_select = mysql_query($sql_select, $mysql_link) or die("ERREUR : get_tableau_jour_feries() <br>\n$sql_select<br>\n".mysql_error());
	$num_select = mysql_num_rows($res_select);
	
	if($num_select!=0)
	{
		while($result_select = mysql_fetch_array($res_select))
		{
			$tab_year[]=$result_select["jf_date"];
		}
	}
	
	mysql_close($mysql_link);

}

?>
